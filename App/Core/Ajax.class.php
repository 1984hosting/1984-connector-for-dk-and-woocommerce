<?php

namespace woo_bookkeeping\App\Core;

class Ajax
{
    private $callback;
    private bool $is_admin;
    private string $action;
    public array $params;

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

    public function doCallback()
    {
        $this->params = $_POST;
        $this->params = apply_filters(PLUGIN_SLUG . '_prepare_ajax_callback_params', $this->params);
        call_user_func($this->callback, $this->params);
    }

    public static function response(array $args)
    {
        if (isset($args['message'])) {
            $args['message'] = __($args['message'], PLUGIN_SLUG);
        }
        echo json_encode($args);
    }

    private function registerActions()
    {
        if ($this->is_admin) {//todo: wp is admin??
            add_action('admin_action_' . $this->action, [$this, 'doCallback']);
        } else {
            add_action('wp_ajax_' . $this->action, [$this, 'doCallback']);
            add_action('wp_ajax_nopriv_' . $this->action, [$this, 'doCallback']);
        }
    }
}