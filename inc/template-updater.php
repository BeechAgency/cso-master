<?php
/**
 * beechnut functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package beechnut
 */


/* 
 * Automatic theme updates from the GitHub repository
 * Care of https://gist.github.com/slfrsn/a75b2b9ef7074e22ce3b. modified by me
 */ 
/*
add_filter('pre_set_site_transient_update_themes', 'automatic_GitHub_updates', 100, 1);

function automatic_GitHub_updates($data) {
  // Theme information
  $theme   = get_stylesheet(); // Folder name of the current theme
  $current = wp_get_theme()->get('Version'); // Get the version of the current theme
  // GitHub information
  $user = 'BeechAgency'; // The GitHub username hosting the repository
  $repo = 'cso-master'; // Repository name as it appears in the URL
  // Get the latest release tag from the repository. The User-Agent header must be sent, as per
  // GitHub's API documentation: https://developer.github.com/v3/#user-agent-required
  $file = json_decode(file_get_contents('https://api.github.com/repos/'.$user.'/'.$repo.'/releases/latest', false,
      	stream_context_create(
			['http' => ['header' => "User-Agent: ".$user."\r\n"],
			'ssl' => ["verify_peer"=>false, "verify_peer_name"=>false]]
		)
  ));
  if($file) {
	$update = filter_var($file->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    // Only return a response if the new version number is higher than the current version
    if($update > $current) {
  	  $data->response[$theme] = array(
	      'theme'       => $theme,
	      // Strip the version number of any non-alpha characters (excluding the period)
	      // This way you can still use tags like v1.1 or ver1.1 if desired
	      'new_version' => $update,
	      'url'         => 'https://github.com/'.$user.'/'.$repo,
	      'package'     => $file->zipball_url,
      );
    }
  }
  return $data;
}
*/

function dump_it($what, $color) {
  echo "<pre style='background-color: $color;'>";
  var_dump($what);
  echo '</pre>';
}

class CSOMASTER_Theme_Updater {
  private $file;    
  private $theme;    
  private $themeObject;
  private $version;    
  private $active;    
  private $username;    
  private $repository;    
  private $authorize_token;
  private $github_response;

  public function __construct( $file ) {
      $this->file = $file;
      $this->set_theme_properties();

      //add_action( 'admin_init', array( $this, 'set_theme_properties' ) );

      return $this;
  }

  public function set_theme_properties() {
      $this->version  = wp_get_theme($this->theme)->get('Version');
      $this->themeObject = wp_get_theme($this->theme);
      $this->active	= $this->theme === get_stylesheet() ? true : false;
  }

  public function set_theme( $theme ) {
      $this->theme = $theme;
  }
  public function set_username( $username ) {
      $this->username = $username;
  }
  public function set_repository( $repository ) {
      $this->repository = $repository;
  }
  public function authorize( $token ) {
      $this->authorize_token = $token;
  }

  private function get_repository_info() {
      if ( is_null( $this->github_response ) ) { // Do we have a response?
        $args = array();
        $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases/latest', $this->username, $this->repository ); // Build URI
          
        $args = array();

        if( $this->authorize_token ) { // Is there an access token?
            $args['headers']['Authorization'] = "token {$this->authorize_token}"; // Set the headers
        }

        //$response = json_decode( wp_remote_retrieve_body( wp_remote_get( $request_uri, $args ) ), true ); // Get JSON and parse it
        $response = json_decode(
          file_get_contents(
            'https://api.github.com/repos/'.$this->username.'/'.$this->repository.'/releases/latest', false,
      	    stream_context_create([
              'http' => ['header' => "User-Agent: ".$this->username."\r\n"],
              'ssl' => ["verify_peer"=>false, "verify_peer_name"=>false]
          ])
        ));

        /*
        dump_it('Github Response', 'aqua');
        dump_it($response, 'aqua');
        */

        if( is_array( $response ) ) { // If it is an array
            $response = current( $response ); // Get the first item
        }

        $this->github_response = $response; // Set it to our property
      }
  }

  public function initialize() {
      add_filter( 'pre_set_site_transient_update_themes', array( $this, 'modify_transient' ), 10, 1 );
      //add_filter( 'plugins_api', array( $this, 'plugin_popup' ), 10, 3);
      add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
      
      // Add Authorization Token to download_package
      add_filter( 'upgrader_pre_download',
          function() {
              add_filter( 'http_request_args', [ $this, 'download_package' ], 15, 2 );
              return false; // upgrader_pre_download filter default return value.
          }
      );
  }

  public function modify_transient( $transient ) {

      /*
      dump_it('Transient', 'lime');
      dump_it($transient, 'lime');

      dump_it( 'Checked Exists', 'green');
      dump_it( property_exists( $transient, 'checked'), 'green');
      */
      

      if( property_exists( $transient, 'checked') ) { // Check if transient has a checked property

        //dump_it($transient->checked, 'yellow');

          if( $checked = $transient->checked ) { // Did Wordpress check for updates?
              $this->get_repository_info(); // Get the repo info

             /*
              dump_it( 'GITHUB RESPONSE within Transient' , 'orange' );
              dump_it( $this->github_response , 'orange' );
              dump_it( gettype($this->github_response === "boolean"), 'orange' );
              */

              if( gettype($this->github_response) === "boolean" ) { return $transient; }

              $github_version = filter_var($this->github_response->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
              
              $out_of_date = version_compare( 
                  $github_version, 
                  $checked[ $this->theme ], 
                  'gt' 
              ); // Check if we're out of date

              /*
              dump_it('Git response tag_name (salmon) and theme (pink)', 'salmon');
              dump_it($this->github_response->tag_name, 'salmon');
              dump_it($checked[ $this->theme ], 'hotpink');
              dump_it('Out of date', 'lightblue');
              dump_it($out_of_date, 'lightblue');
              */

              if( $out_of_date ) {

                  $new_files = $this->github_response->zipball_url; // Get the ZIP
                    
                  $slug = current( explode('/', $this->theme ) ); // Create valid slug

                  $theme = array( // setup our theme info
                      'url' => 'https://beech.agency', //$this->themeObject["ThemeURI"],
                      'slug' => $slug,
                      'package' => $new_files,
                      'new_version' => $this->github_response->tag_name
                  );

                  $transient->response[$this->theme] = $theme; // Return it in response

              }
          }
      }

      //dump_it('Filtered Transient', 'lightblue');
      //dump_it($transient, 'lightblue');

      return $transient; // Return filtered transient
  }

/*
  public function plugin_popup( $result, $action, $args ) {

      if( ! empty( $args->slug ) ) { // If there is a slug
          
          if( $args->slug == current( explode( '/' , $this->basename ) ) ) { // And it's our slug

              $this->get_repository_info(); // Get our repo info

              // Set it to an array
              $plugin = array(
                  'name'				=> $this->plugin["Name"],
                  'slug'				=> $this->basename,
                  'requires'					=> '5.1',
                  'tested'						=> '6.0.2',
                  'rating'						=> '100.0',
                  'num_ratings'				=> '5',
                  'downloaded'				=> '5',
                  'added'							=> '2020-07-08',
                  'version'			=> $this->github_response['tag_name'],
                  'author'			=> $this->plugin["AuthorName"],
                  'author_profile'	=> $this->plugin["AuthorURI"],
                  'last_updated'		=> $this->github_response['published_at'],
                  'homepage'			=> $this->plugin["PluginURI"],
                  'short_description' => $this->plugin["Description"],
                  'sections'			=> array(
                      'Description'	=> $this->plugin["Description"],
                      'Updates'		=> $this->github_response['body'],
                  ),
                  'download_link'		=> $this->github_response['zipball_url']
              );

              return (object) $plugin; // Return the data
          }

      }
      return $result; // Otherwise return default
  }*/

  public function download_package( $args, $url ) {

    //dump_it('Download Package', 'red');
    //dump_it($args, 'red');

      if ( null !== $args['filename'] ) {
          if( $this->authorize_token ) { 
              $args = array_merge( $args, array( "headers" => array( "Authorization" => "token {$this->authorize_token}" ) ) );
          }
      }
      
      remove_filter( 'http_request_args', [ $this, 'download_package' ] );

      return $args;
  }

  public function after_install( $response, $hook_extra, $result ) {

    //dump_it('After install', 'hotpink');
    //dump_it($result, 'hotpink');

      global $wp_filesystem; // Get global FS object

      $install_directory = get_theme_root(). '/' . $this->theme ; // Our theme directory
      $wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the theme dir
      $result['destination'] = $install_directory; // Set the destination for the rest of the stack

  /*
      if ( $this->active ) { // If it was active
          activate_plugin( $this->basename ); // Reactivate
      }*/

      return $result;
  }
}


$updater = new CSOMASTER_Theme_Updater( __FILE__ );
$updater->set_username( 'BeechAgency' );
$updater->set_repository( 'cso-master' );
$updater->set_theme('cso-master'); 


$updater->initialize();

//dump_it($updater, 'white');

/*
$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
*/

