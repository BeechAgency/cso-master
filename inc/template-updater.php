<?php
/**
 * CatholicSchoolsMN functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package CatholicSchoolsMN
 */

 class CatholicSchoolsMN_Master_Theme_Updater {
    private $file;    
    private $theme;    
    private $themeObject;
    private $version;    
    private $active;    
    private $username;    
    private $repository;    
    private $authorize_token;
    private $github_response;
    private $package_url;
    private $request_uri;
    private $logging = false;
    private $log_file;


    public function __construct( $file ) {
        $this->file = $file;
        $this->set_theme_properties();
		
		$upload_dir = wp_upload_dir();
		$this->log_file = trailingslashit( $upload_dir['basedir'] ) . 'update_log.txt';
        //$this->log_file = __DIR__ . '/update_log.txt'; // Set log file path

        return $this;
    }

    // Provides logging
    private function log($message) {
        if ( !$this->logging ) return;

        $timestamp = date("Y-m-d H:i:s");
        file_put_contents($this->log_file, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
        //error_log( print_r("GitUpdater: [$timestamp] $message"));
    }

    public function set_logging( $status = false ) {
        $this->logging =  $status;
    }

    public function set_theme_properties() {
        $this->version  = wp_get_theme($this->theme)->get('Version');
        $this->themeObject = wp_get_theme($this->theme);
        $this->active	= $this->theme === get_stylesheet() ? true : false;
        $this->theme_dir = get_theme_root();
        $this->theme_name = get_option('stylesheet');
        $this->theme_template = get_stylesheet_directory(); // Folder name of the current theme
        $this->theme_uri = get_stylesheet_directory_uri(); // URL of the current theme folder
    }

    public function set_theme( $theme ) {
        $this->theme = $theme;
        $this->theme_slug = $theme;
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
        if ( !is_null( $this->github_response ) ) {
            return; // We already have a response so bail.
        }
    
        $args = array();
        $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases/latest', $this->username, $this->repository ); // Build URI
    
        $this->request_uri = $request_uri;
        
        $this->log("Request URL: ". $request_uri);
    
        $headers = array(
            'User-Agent: ' . $this->username,
        );
    
        if ($this->authorize_token) {
            $this->log("handling request with authorization token");
            $headers[] = 'Authorization: token ' . $this->authorize_token;
        }
    
        $ch = curl_init($request_uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($http_code == 200) {
            $this->github_response = json_decode($response);
            $this->log("GitHub response: " . json_encode($this->github_response));

        } else {
            $this->log("GitHub error: " . $response);
        }
    
        return;
    }

    public function initialize() {
        $this->log("Initializing GitHub Updater for ". $this->theme);

        $details = array();

        $details['theme_name'] = $this->theme;
        $details['theme_dir'] = $this->theme_dir;
        $details['theme_slug'] = $this->theme_slug;
        $details['active'] = $this->active;
        $details['theme_template'] = $this->theme_template;
        $details['theme_uri'] = $this->theme_uri;
        $details['version'] = $this->version;

        $this->log("Details are: ". json_encode($details));

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
        $this->log("Modifying transient for theme: " . $this->theme);

        if( !property_exists( $transient, 'checked') ) {
            return $transient;
        }
        if( !$transient->checked ) {
            return $transient; // Did Wordpress check for updates?
        }

        $checked = $transient->checked;
        
        $this->log("Checking repository info: ". $this->theme);
        $this->get_repository_info(); // Get the repo info
        

        if( gettype($this->github_response) === "boolean" ) { 
            return $transient; 
        }

        $this->log("Finding the version ". print_r($this->github_response->tag_name, true));
        $github_version = filter_var($this->github_response->tag_name, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        $out_of_date = version_compare( 
            $github_version, 
            $checked[ $this->theme ], 
            'gt' 
        ); // Check if we're out of date

        $this->log("Repo version checked and compared: ". $out_of_date .' | Remote: '.$github_version .' | Local: '. $checked[ $this->theme ]);

        // If she not out of date get outta here.
        if( !$out_of_date )  {
            return $transient; 
        }

        $this->log("Theme out of date");

        $git_response = $this->github_response;

        $new_files = $this->github_response->zipball_url; // Get the ZIP

        // If there are theme assets attached, use those instead!
        if( isset($git_response->assets) && is_countable($git_response->assets) && count($git_response->assets) > 0 ) {
            $new_files = $git_response->assets[0]->browser_download_url;
        }

        if (isset($git_response->assets[0]->id)) {
            $asset_id = $this->github_response->assets[0]->id;
            $this->log("Found asset ID: $asset_id");
            $new_files = "https://api.github.com/repos/{$this->username}/{$this->repository}/releases/assets/{$asset_id}";
        }

        $slug = current( explode('/', $this->theme ) ); // Create valid slug
        $this->package_url = $new_files;

        $this->log("Download url: ".$new_files);
        $this->log("new slug: ". $slug);


        $theme = array( // setup our theme info
            'url' => 'https://github.com/'.$this->username.'/'.$this->repository, //$this->themeObject["ThemeURI"],
            'slug' => 'beechagency2023',
            'package' => $new_files,
            'new_version' => $github_version
        );


        $this->log("Setting transient response with theme info: " . json_encode($theme));

        $transient->response[$this->theme] = $theme; // Return it in response

        $this->log("Modified transient for ". $this->theme." | " . json_encode($transient) );

        return $transient; // Return filtered transient
    }

    public function download_package( $args, $url ) {
        if (strpos($url, $this->username . '/' . $this->repository) === false) {
            $this->log("Immediately returning args. ". print_r($args, true));
            return $args;
        }
    
        if ($this->authorize_token) {
            $this->log("Secure download required for $url");
            if (!isset($args['headers'])) {
                $args['headers'] = [];
            }
    
            $args['headers']['Authorization'] = "token {$this->authorize_token}";
            $args['headers']['Accept'] = "application/octet-stream"; // Important for GitHub asset downloads
        }
    
        $this->log("Download Package Args (after modification): " . json_encode($args));
        remove_filter('http_request_args', [$this, 'download_package']);
    
        return $args;
    }


    public function after_install( $response, $hook_extra, $result ) {
        global $wp_filesystem; // Get global FS object

        $this->log("AFTER INSTALL BABY!!!!!!!" );

        $install_directory = get_theme_root(). '/' . $this->theme ; // Our theme directory
        $wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the theme dir
        $result['destination'] = $install_directory; // Set the destination for the rest of the stack

        $this->log("attempt after install: ". $this->theme);

        return $result;
    }
}

$update_key = get_option('csomasterchild_updates_key', null );

$updater = new CatholicSchoolsMN_Master_Theme_Updater( __FILE__ );
$updater->set_username( 'BeechAgency' );
$updater->set_repository( 'cso-master' );
$updater->set_theme('cso-master'); 
$updater->set_logging(false);

if( $update_key ) {
    $updater->authorize($update_key);    
}

$updater->initialize();
