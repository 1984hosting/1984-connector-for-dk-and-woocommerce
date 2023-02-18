<?php
/**
 * The file that defines the Ajax class
 *
 * A class definition that includes attributes and functions of the Ajax class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Core
 */

namespace woocoo\App\Core;

/**
 * Class Ajax
 */
class Ajax
{
    private $callback;

    private bool $is_admin;

    private string $action;

    public array $params;

    /**
     * Construct
     *
     * @param string $action
     * @param callable $callback
     * @param bool $is_admin
     */
    public function __construct(string $action, callable $callback, bool $is_admin = true)
    {
        if (!$action || !is_callable($callback)) {
            throw new Exception('bad ajax'); //todo: add normal exception text
        }

        $this->action = $action;
        $this->callback = $callback;
        $this->is_admin = $is_admin;

        $this->registerActions();
    }

    /**
     * Do callback
     *
     * @return void
     */
    public function doCallback()
    {
        $this->params = $_POST;
        $this->params = apply_filters(PLUGIN_SLUG . '_prepare_ajax_callback_params', $this->params);
        call_user_func($this->callback, $this->params);
    }

    /**
     * Response
     *
     * @param array $args
     * @return void
     */
    public static function response(array $args)
    {
        if (isset($args['message'])) {
            $args['message'] = __($args['message'], PLUGIN_SLUG);
        }
        echo json_encode($args);
    }

    /**
     * Register Actions
     *
     * @return void
     */
    private function registerActions()
    {
        if ($this->is_admin) {
            add_action('admin_action_' . $this->action, [$this, 'doCallback']);
        } else {
            add_action('wp_ajax_' . $this->action, [$this, 'doCallback']);
            add_action('wp_ajax_nopriv_' . $this->action, [$this, 'doCallback']);
        }
    }
}