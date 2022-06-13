<?php
namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\WP_Notice;

trait API
{
    public static array $dkPlus = [];
    private static $token = '';
    private string $api_url = 'https://api.dkplus.is/api/v1';

    public function getToken()
    {
        if (!empty(self::$dkPlus[Main::$module_slug]['token'])) {
            self::$token = self::$dkPlus[Main::$module_slug]['token'];
        } else {
            $this->setToken();
        }
    }

    //todo: check valid token, if isn't valid -> update
    private function setToken(): void
    {
        $this->createToken();

        if (empty(self::$token)) {
            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
            return;
        }

        self::$dkPlus[Main::$module_slug]['token'] = self::$token;
        update_option(PLUGIN_SLUG, self::$dkPlus, 'no');
    }

    private function createToken(): void
    {
        $method = '/token';
        $args = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(self::$dkPlus[Main::$module_slug]['login'] . ':' . self::$dkPlus[Main::$module_slug]['password']),
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'Description' => 'Woo bookkeeping',
            ],
            'method' => 'POST',
        ];

        $result = $this->request($method, $args);

        if (!empty($result['Token'])) {
            self::$token = $result['Token'];
        }
    }

    private function request(string $method, array $args): array
    {
        $response = wp_remote_request($this->api_url . $method, $args);
        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }
}