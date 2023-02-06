<?php

declare(strict_types=1);

namespace woocoo\App\Core;

class WP_Notice
{
    private string $class;
    private string $message;

    function __construct(string $class, string $message)
    {
        $this->class = $class;
        $this->message = $message;

        return add_action('admin_notices', [$this, 'createHTML']);
    }

    public function createHTML(): void
    {
        printf('<div class="%1$s notice is-dismissible"><p>%2$s</p></div>', esc_attr($this->class), esc_html(__($this->message, PLUGIN_SLUG)));
    }
}
