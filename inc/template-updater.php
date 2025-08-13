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

        return $this;
    }

    // Provides logging
    private function log($message) {
        if ( !$this->logging ) return;

        $timestamp = date("Y-m-d H:i:s");
        error_log("GitUpdater [$timestamp]: $message");
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
            $this->log("Repository info already cached, skipping API call");
            return; // We already have a response so bail.
        }
    
        $args = array();
        $request_uri = sprintf( 'https://api.github.com/repos/%s/%s/releases/latest', $this->username, $this->repository ); // Build URI
    
        $this->request_uri = $request_uri;
        
        $this->log("=== GITHUB API REQUEST ===");
        $this->log("Request URL: ". $request_uri);
        $this->log("Username: " . $this->username);
        $this->log("Repository: " . $this->repository);
        $this->log("Has auth token: " . ($this->authorize_token ? 'YES' : 'NO'));
        
        error_log("Request URI is". $request_uri);
    
        $headers = array(
            'User-Agent: ' . $this->username,
        );
    
        if ($this->authorize_token) {
            $this->log("Adding authorization token to headers");
            $headers[] = 'Authorization: token ' . $this->authorize_token;
        } else {
            $this->log("WARNING: No authorization token - may hit rate limits");
        }
        
        $this->log("Request headers: " . json_encode($headers));
    
        $ch = curl_init($request_uri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $this->log("Making cURL request...");
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        $curl_info = curl_getinfo($ch);
        curl_close($ch);
        
        $this->log("=== GITHUB API RESPONSE ===");
        $this->log("HTTP Code: " . $http_code);
        $this->log("cURL Error: " . ($curl_error ?: 'None'));
        $this->log("Response size: " . strlen($response) . " bytes");
        $this->log("Content type: " . ($curl_info['content_type'] ?? 'Unknown'));
    
        if ($http_code == 200) {
            $this->github_response = json_decode($response);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->log("JSON DECODE ERROR: " . json_last_error_msg());
                $this->log("Raw response (first 500 chars): " . substr($response, 0, 500));
                return;
            }
            
            $this->log("GitHub API call successful");
            $this->log("Release tag: " . ($this->github_response->tag_name ?? 'Not found'));
            $this->log("Release name: " . ($this->github_response->name ?? 'Not found'));
            $this->log("Assets count: " . (isset($this->github_response->assets) ? count($this->github_response->assets) : 0));
            
            if (isset($this->github_response->assets) && count($this->github_response->assets) > 0) {
                foreach ($this->github_response->assets as $index => $asset) {
                    $this->log("Asset $index: " . $asset->name . " (ID: " . $asset->id . ", Size: " . $asset->size . " bytes)");
                    $this->log("Asset $index download URL: " . $asset->browser_download_url);
                }
            } else {
                $this->log("No assets found in release, will use zipball_url: " . ($this->github_response->zipball_url ?? 'Not found'));
            }

        } else {
            $this->log("=== GITHUB API ERROR ===");
            $this->log("HTTP Code: " . $http_code);
            $this->log("Error response: " . $response);
            
            // Try to decode error response
            $error_data = json_decode($response);
            if ($error_data && isset($error_data->message)) {
                $this->log("GitHub error message: " . $error_data->message);
            }
        }
    
        return;
    }

    public function initialize() {
        $this->log("=== INITIALIZING GITHUB UPDATER ===");
        $this->log("Theme: " . $this->theme);
        $this->log("Username: " . $this->username);
        $this->log("Repository: " . $this->repository);

        $details = array();

        $details['theme_name'] = $this->theme;
        $details['theme_dir'] = $this->theme_dir;
        $details['theme_slug'] = $this->theme_slug;
        $details['active'] = $this->active;
        $details['theme_template'] = $this->theme_template;
        $details['theme_uri'] = $this->theme_uri;
        $details['version'] = $this->version;

        $this->log("Theme details: ". json_encode($details));

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
        
        // Add filter to capture HTTP responses for debugging
        add_filter( 'http_response', [ $this, 'log_http_response' ], 10, 3 );
        
        $this->log("GitHub Updater initialized successfully");
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

        $this->log("=== DETERMINING DOWNLOAD URL ===");
        $this->log("Default zipball URL: " . $new_files);
        
        // If there are theme assets attached, use those instead!
        if( isset($git_response->assets) && is_countable($git_response->assets) && count($git_response->assets) > 0 ) {
            $this->log("Found " . count($git_response->assets) . " assets in release");
            
            foreach ($git_response->assets as $index => $asset) {
                $this->log("Asset $index: " . $asset->name . " (Size: " . $asset->size . " bytes)");
            }
            
            $asset = $git_response->assets[0];
            $this->log("Using first asset: " . $asset->name);
            $this->log("Asset browser_download_url: " . $asset->browser_download_url);
            
            // Use the API endpoint for private repos (requires auth)
            if (isset($asset->id)) {
                $asset_id = $asset->id;
                $this->log("Found asset ID: $asset_id");
                $new_files = "https://api.github.com/repos/{$this->username}/{$this->repository}/releases/assets/{$asset_id}";
                $this->log("Using API asset download URL: " . $new_files);
            } else {
                $new_files = $asset->browser_download_url;
                $this->log("Using browser download URL: " . $new_files);
            }
        } else {
            $this->log("No assets found, using zipball URL");
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
        $this->log("=== DOWNLOAD PACKAGE FILTER ===");
        $this->log("Download URL: " . $url);
        $this->log("Original args: " . json_encode($args));
        
        // Check if this is our repository URL
        $is_our_repo = (strpos($url, $this->username . '/' . $this->repository) !== false);
        $this->log("Is our repository: " . ($is_our_repo ? 'YES' : 'NO'));
        
        if (!$is_our_repo) {
            $this->log("Not our repository, returning original args");
            return $args;
        }
        
        $this->log("Processing download for our repository");
    
        if ($this->authorize_token) {
            $this->log("Adding authorization for secure download");
            if (!isset($args['headers'])) {
                $args['headers'] = [];
            }
    
            $args['headers']['Authorization'] = "token {$this->authorize_token}";
            
            // For GitHub API asset downloads, we need specific headers
            if (strpos($url, '/releases/assets/') !== false) {
                $this->log("This is a GitHub API asset download - adding Accept header");
                $args['headers']['Accept'] = "application/octet-stream";
            }
            
            $this->log("Added authorization headers");
        } else {
            $this->log("WARNING: No authorization token available for download");
        }
        
        // Add additional debugging options
        $args['timeout'] = 300; // 5 minutes timeout
        $args['redirection'] = 5; // Allow redirects
        
        $this->log("Final download args: " . json_encode($args));
        
        // Remove the filter to prevent infinite loops
        remove_filter('http_request_args', [$this, 'download_package']);
    
        return $args;
    }


    public function log_http_response( $response, $args, $url ) {
        // Only log responses for our repository
        if (strpos($url, $this->username . '/' . $this->repository) !== false) {
            $this->log("=== HTTP RESPONSE DEBUG ===");
            $this->log("Response URL: " . $url);
            
            if (is_wp_error($response)) {
                $this->log("WP_Error occurred: " . $response->get_error_message());
                $this->log("Error codes: " . implode(', ', $response->get_error_codes()));
            } else {
                $response_code = wp_remote_retrieve_response_code($response);
                $response_message = wp_remote_retrieve_response_message($response);
                $content_type = wp_remote_retrieve_header($response, 'content-type');
                $content_length = wp_remote_retrieve_header($response, 'content-length');
                
                $this->log("Response Code: " . $response_code);
                $this->log("Response Message: " . $response_message);
                $this->log("Content-Type: " . $content_type);
                $this->log("Content-Length: " . $content_length);
                
                $body = wp_remote_retrieve_body($response);
                $body_length = strlen($body);
                $this->log("Actual body length: " . $body_length . " bytes");
                
                // Check if it looks like a ZIP file
                $is_zip = (substr($body, 0, 2) === 'PK');
                $this->log("Looks like ZIP file: " . ($is_zip ? 'YES' : 'NO'));
                
                if (!$is_zip && $body_length < 1000) {
                    $this->log("Response body (not ZIP, showing first 500 chars): " . substr($body, 0, 500));
                }
                
                // Log response headers
                $headers = wp_remote_retrieve_headers($response);
                if ($headers) {
                    $this->log("Response headers: " . json_encode($headers->getAll()));
                }
            }
        }
        
        return $response;
    }

    public function after_install( $response, $hook_extra, $result ) {
        global $wp_filesystem; // Get global FS object

        $this->log("=== AFTER INSTALL ===");
        $this->log("Hook extra: " . json_encode($hook_extra));
        $this->log("Result: " . json_encode($result));

        $install_directory = get_theme_root(). '/' . $this->theme ; // Our theme directory
        $this->log("Moving from: " . $result['destination']);
        $this->log("Moving to: " . $install_directory);
        
        $move_result = $wp_filesystem->move( $result['destination'], $install_directory ); // Move files to the theme dir
        $this->log("Move result: " . ($move_result ? 'SUCCESS' : 'FAILED'));
        
        $result['destination'] = $install_directory; // Set the destination for the rest of the stack

        $this->log("After install completed for: ". $this->theme);

        return $result;
    }
}

$update_key = get_option('csomaster_updates_key', null );

$updater = new CatholicSchoolsMN_Master_Theme_Updater( __FILE__ );
$updater->set_username( 'BeechAgency' );
$updater->set_repository( 'cso-master' );
$updater->set_theme('cso-master'); 
$updater->set_logging(false);

if( $update_key ) {
    $updater->authorize($update_key);    
}

$updater->initialize();
