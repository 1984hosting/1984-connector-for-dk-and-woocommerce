<?php
/**
 * Handles GitHub Update.
 *
 * @package  WooCoo\updater
 * @version  0.1
 */

namespace woocoo;

/**
 * Class updater
 */
class updater {

    /**
     * The full path and filename of the plugin
     *
     * @var string
     */
    private string $file;

    /**
     * Plugin's metadata
     *
     * @var array
     */
    private array $plugin;

    /**
     * The basename of a plugin
     *
     * @var string
     */
    private string $basename;

    /**
     *  True, if in the active plugins list. False, not in the list.
     *
     * @var bool
     */
    private bool $active;

    /**
     * The account owner of the repository
     *
     * @var string
     */
    private string $username;

    /**
     * The name of the repository
     *
     * @var string
     */
    private string $repository;

    /**
     * Personal access token
     *
     * @var string string
     */
    private string $authorize_token;

    /**
     * Information about a release
     *
     * @var array
     */
    private array $github_response = array();

    /**
     * True, if the download filter added. False, not.
     *
     * @var bool
     */
    private bool $download_filter_added = false;

    /**
     * Constructor
     *
     * @param $file
     */
    public function __construct( $file ) {
        $this->file = $file;
        $this->set_plugin_properties();
        return $this;
    }

    /**
     *  Set a plugin properties
     *
     * @return void
     */
    public function set_plugin_properties() {
        // Ensure get_plugins() function is available.
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $this->plugin = get_plugin_data($this->file);
        $this->basename = plugin_basename($this->file);
        $this->active = is_plugin_active($this->basename);

        if ($this->plugin) {
            $repositoryUrl = $this->plugin['UpdateURI'];
            $path = parse_url($repositoryUrl, PHP_URL_PATH);
            if ( preg_match('@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches) ) {
                $this->username = $matches['username'];
                $this->repository = $matches['repository'];
            }
        }

    }

    /**
     * Authorize access to GitHub repository
     *
     * @param $token
     * @return void
     */
    public function authorize( $token ) {
        $this->authorize_token = $token;
        add_filter('upgrader_pre_download', [$this, 'add_http_request_filter'], 10, 1);
    }

    /**
     * Check if is authentication enabled
     *
     * @return bool
     */
    public function is_authentication_enabled() {
        return !empty($this->authorize_token);
    }

    /**
     * Add http request filter
     *
     * @param $result
     * @return mixed
     */
    public function add_http_request_filter( $result ) {
        if ( !$this->download_filter_added && $this->is_authentication_enabled() ) {
            add_filter('http_request_args', [$this, 'set_update_download_headers'], 10, 2);
            add_action('requests-requests.before_redirect', [$this, 'remove_auth_header_from_redirects'], 10, 4);
            $this->download_filter_added = true;
        }
        return $result;
    }

    /**
     * Set update download headers
     *
     * @param $request_args
     * @param $url
     * @return array
     */
    public function set_update_download_headers( $request_args, $url = '' ) {
        //Use Basic authentication, but only if the download is from our repository.
        if ( $this->is_authentication_enabled() ) {
            $request_args['headers']['Authorization'] = $this->get_authorization_header();
        }
        return $request_args;
    }

    /**
     * Get authorization header
     *
     * @return string
     */
    protected function get_authorization_header() {
        return 'Basic ' . base64_encode($this->username . ':' . $this->authorize_token);
    }

    /**
     * Remove auth header from redirects
     *
     * @param $location
     * @param $headers
     * @return void
     */
    public function remove_auth_header_from_redirects( &$location, &$headers ) {
        //Remove the header.
        if ( isset($headers['Authorization']) ) {
            unset($headers['Authorization']);
        }
    }

    /**
     * Get repository info
     *
     * @return void
     */
    private function get_repository_info() {
        if (empty($this->github_response)) {
            $repositoryUrl = $this->plugin['UpdateURI'];
            $path = parse_url($repositoryUrl, PHP_URL_PATH);
            if ( preg_match('@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches) ) {
                $this->username = $matches['username'];
                $this->repository = $matches['repository'];
                $request_uri = sprintf('https://api.github.com/repos%s/releases', $path);
                $args = [
                    'sslverify' => false,
                    'timeout'     => 0,
                    'redirection' => 10,
                    'httpversion' => '1.0',
                    'headers'     => array(
                        'Authorization' => 'token ' . $this->authorize_token,
                        'User-Agent' => 'woocoo/updater/0.1'
                    ),
                ];
                $response = wp_remote_get($request_uri, $args);
                $response = json_decode(wp_remote_retrieve_body($response), true);

                if (is_array($response)) {
                    $response = current($response);
                }
                $this->github_response = $response;
            } else {
                throw new \InvalidArgumentException('Invalid GitHub repository URL: "' . $repositoryUrl . '"');
            }
        }
    }

    /**
     * Get repository clones count
     *
     * @return false|mixed
     */
    private function get_repository_clones_count() {
        $repositoryUrl = $this->plugin['UpdateURI'];
        $path = parse_url($repositoryUrl, PHP_URL_PATH);
        if ( preg_match('@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches) ) {
            $this->username = $matches['username'];
            $this->repository = $matches['repository'];
            $request_uri = sprintf('https://api.github.com/repos%s/traffic/clones', $path);
            $args = [
                'sslverify' => false,
                'timeout'     => 0,
                'redirection' => 10,
                'httpversion' => '1.0',
                'headers'     => array(
                    'Authorization' => 'token ' . $this->authorize_token,
                    'User-Agent' => 'woocoo/updater/0.1'
                ),
            ];
            $response = wp_remote_get($request_uri, $args);
            if ($response) {
                $response = json_decode(wp_remote_retrieve_body($response), true);
                if (isset($response['count'])) {
                    return $response['count'];
                }
            }
        } else {
            throw new \InvalidArgumentException('Invalid GitHub repository URL: "' . $repositoryUrl . '"');
        }
        return false;
    }

    /**
     * Get readme file info
     *
     * @return false|string
     */
    private function get_readme_info() {
        $repositoryUrl = $this->plugin['UpdateURI'];
        $path = parse_url($repositoryUrl, PHP_URL_PATH);
        if ( preg_match('@^/?(?P<username>[^/]+?)/(?P<repository>[^/#?&]+?)/?$@', $path, $matches) ) {
            $this->username = $matches['username'];
            $this->repository = $matches['repository'];
            $request_uri = sprintf('https://raw.githubusercontent.com%s/main/README.md', $path);
            $args = [
                'sslverify' => false,
                'timeout'     => 0,
                'redirection' => 10,
                'httpversion' => '1.0',
                'headers'     => array(
                    'Authorization' => 'token ' . $this->authorize_token,
                    'User-Agent' => 'woocoo/updater/0.1'
                ),
            ];
            $response = wp_remote_get($request_uri, $args);
            if ($response) {
                return $this->render_markdown_text( wp_remote_retrieve_body($response) );
            }
        } else {
            throw new \InvalidArgumentException('Invalid GitHub repository URL: "' . $repositoryUrl . '"');
        }
        return false;
    }

    /**
     * Render a Markdown document
     *
     * @param $text
     * @return false|string
     */
    private function render_markdown_text( $text ) {
        $request_uri = 'https://api.github.com/markdown/raw';
        $args = [
            'sslverify' => false,
            'timeout'     => 0,
            'redirection' => 10,
            'httpversion' => '1.0',
            'headers'     => array(
                'Authorization' => 'token ' . $this->authorize_token,
                'Accept' => 'application/vnd.github+json',
                'Content-Type' => 'text/plain',
                'User-Agent' => 'woocoo/updater/0.1'
            ),
            'method'     => 'POST',
            'body'       => $text,

        ];
        $response = wp_remote_post($request_uri, $args);
        if ($response) {
            return wp_remote_retrieve_body($response);
        }
        return false;
    }

    /**
     * Initialize plugin
     *
     * @return void
     */
    public function initialize() {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'update_site_transient'], 15, 1);
        add_filter('plugins_api', [$this, 'plugins_api'], 99, 3);
        add_filter('upgrader_post_install', [$this, 'after_install'], 10, 3);
    }

    /**
     * Update site transient
     *
     * @param $transient
     * @return mixed|\stdClass
     */
    public function update_site_transient( $transient ) {
        // needed to fix PHP 7.4 warning.
        if ( ! \is_object( $transient ) ) {
            $transient = new \stdClass();
        }

        $this->get_repository_info();

        $slug = current(explode('/', $this->basename));
        $new_files = $this->github_response['zipball_url'];

        $plugin = [
            'url' => $this->plugin['PluginURI'],
            'icons' => [
                'svg' => PLUGIN_URL . 'templates/assets/images/icon.svg',
            ],
            'slug' => $slug,
            'package' => $new_files,
            'new_version' => $this->github_response['tag_name']
        ];

        $out_of_date = version_compare($this->github_response['tag_name'], $this->plugin['Version'], 'gt');
        if ($out_of_date) {
            $transient->response[$this->basename] = (object) $plugin;
        } else {
            // Add repo without update to $transient->no_update for 'View details' link.
            if ( ! isset( $transient->no_update[ $this->basename ] ) ) {
                $transient->no_update[ $this->basename ] = (object) $plugin;
            }
        }

        return $transient;
    }

    /**
     * Get plugin data
     *
     * @param $false
     * @param $action
     * @param $response
     * @return mixed|object
     */
    public function plugins_api( $false, $action, $response ) {
        if ( ! ( 'plugin_information' === $action ) ) {
            return $false;
        }

        if (!empty($response->slug)) {
            if ($response->slug == current(explode('/' , $this->basename))) {
                $this->get_repository_info();

                $readme = $this->get_readme_info();

                $plugin = [
                    'slug' => $response->slug,
                    'plugin_name' => $this->plugin['Name'],
                    'name' => $this->plugin['Name'],
                    'version' => $this->github_response['tag_name'],
                    'author' => $this->plugin['AuthorName'],
                    'author_profile' => $this->plugin['AuthorURI'],
                    'last_updated' => $this->github_response['published_at'],
                    'active_installs'  => $this->get_repository_clones_count(),
                    'homepage' => $this->plugin['PluginURI'],
                    'short_description' => substr( strip_tags( trim( $this->plugin['Description'] ) ), 0, 175 ) . '...',
                    'sections' => [
                        'description' => ($readme)?:$this->plugin['Description'],
                        'changelog' => $this->github_response['body']?:__('No Updates', PLUGIN_SLUG),
                    ],
                    'icons' => [
                        'svg' => PLUGIN_URL . 'templates/assets/images/icon.svg',
                    ],
                    'banners' => [
                        'low' => PLUGIN_URL . 'templates/assets/images/banner-772x250.jpg',
                        'high' => PLUGIN_URL . 'templates/assets/images/banner-1544x500.jpg'
                    ],
                    'download_link' => $this->github_response['zipball_url']
                ];
                return (object) $plugin;
            }
        }

        return $false;
    }

    /**
     * Finish plugin installation
     *
     * @param $response
     * @param $hook_extra
     * @param $result
     * @return mixed
     */
    public function after_install( $response, $hook_extra, $result ) {
        global $wp_filesystem;

        $install_directory = plugin_dir_path($this->file);
        $wp_filesystem->move($result['destination'], $install_directory);
        $result['destination'] = $install_directory;

        if ($this->active) {
            activate_plugin($this->basename);
        }

        return $result;
    }
}
