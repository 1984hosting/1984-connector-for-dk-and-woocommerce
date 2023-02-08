<?php
namespace woocoo;

class updater {
    private $file;
    private $plugin;
    private $basename;
    private $active;
    private $username;
    private $repository;
    private $authorize_token;
    private $github_response;
    private $downloadFilterAdded = false;

    public function __construct($file) {
        $this->file = $file;
        add_action('admin_init', [$this, 'set_plugin_properties']);
        return $this;
    }

    public function set_plugin_properties() {
        $this->plugin = get_plugin_data($this->file);
        $this->basename = plugin_basename($this->file);
        $this->active = is_plugin_active($this->basename);
    }

    public function authorize($token) {
        $this->authorize_token = $token;
        add_filter('upgrader_pre_download', [$this, 'addHttpRequestFilter'], 10, 1);
    }

    public function isAuthenticationEnabled() {
        return !empty($this->authorize_token);
    }

    public function addHttpRequestFilter($result) {
        if ( !$this->downloadFilterAdded && $this->isAuthenticationEnabled() ) {
            add_filter('http_request_args', [$this, 'setUpdateDownloadHeaders'], 10, 2);
            add_action('requests-requests.before_redirect', [$this, 'removeAuthHeaderFromRedirects'], 10, 4);
            $this->downloadFilterAdded = true;
        }
        return $result;
    }

    public function setUpdateDownloadHeaders($requestArgs, $url = '') {
        //Use Basic authentication, but only if the download is from our repository.
        if ( $this->isAuthenticationEnabled() ) {
            $requestArgs['headers']['Authorization'] = $this->getAuthorizationHeader();
        }
        return $requestArgs;
    }

    protected function getAuthorizationHeader() {
        return 'Basic ' . base64_encode($this->username . ':' . $this->authorize_token);
    }

    public function removeAuthHeaderFromRedirects(&$location, &$headers) {
        //Remove the header.
        if ( isset($headers['Authorization']) ) {
            unset($headers['Authorization']);
        }
    }

    private function get_repository_info() {
        if (is_null($this->github_response)) {
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

                if ($this->authorize_token) {
                    $response['zipball_url'] = add_query_arg('access_token', $this->authorize_token, $response['zipball_url']);
                }
                $this->github_response = $response;
            } else {
                throw new InvalidArgumentException('Invalid GitHub repository URL: "' . $repositoryUrl . '"');
            }
        }
    }

    public function initialize() {
        add_filter('pre_set_site_transient_update_plugins', [$this, 'modify_transient'], 10, 1);
        add_filter('plugins_api', [$this, 'plugin_popup'], 10, 3);
        add_filter('upgrader_post_install', [$this, 'after_install'], 10, 3);
    }

    public function modify_transient($transient) {
        if (property_exists($transient, 'checked')) {
            if ($checked = $transient->checked) {
                $this->get_repository_info();

                $out_of_date = version_compare($this->github_response['tag_name'], $checked[$this->basename], 'gt');

                if ($out_of_date) {
                    $new_files = $this->github_response['zipball_url'];
                    $slug = current(explode('/', $this->basename));

                    $plugin = [
                        'url' => $this->plugin['PluginURI'],
                        'slug' => $slug,
                        'package' => $new_files,
                        'new_version' => $this->github_response['tag_name']
                    ];

                    $transient->response[$this->basename] = (object) $plugin;
                }
            }
        }

        return $transient;
    }

    public function plugin_popup($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return false;
        }

        if (!empty($args->slug)) {
            if ($args->slug == current(explode('/' , $this->basename))) {
                $this->get_repository_info();

                $plugin = [
                    'name' => $this->plugin['Name'],
                    'slug' => $this->basename,
                    'version' => $this->github_response['tag_name'],
                    'author' => $this->plugin['AuthorName'],
                    'author_profile' => $this->plugin['AuthorURI'],
                    'last_updated' => $this->github_response['published_at'],
                    'homepage' => $this->plugin['PluginURI'],
                    'short_description' => $this->plugin['Description'],
                    'sections' => [
                        'Description' => $this->plugin['Description'],
                        'Updates' => $this->github_response['body'],
                    ],
                    'download_link' => $this->github_response['zipball_url']
                ];

                return (object) $plugin;
            }
        }

        return $result;
    }

    public function after_install($response, $hook_extra, $result) {
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
