<?php
namespace woo_bookkeeping\Modules\dkPlus;

use woo_bookkeeping\App\Core\WP_Notice;

trait API
{
    private static array $dkPlus;
    private static string $token = '';
    private string $api_url = 'https://api.dkplus.is/api/v1';

    public function getToken()
    {
        if (!empty($data->dkPlus['token'])) {
            self::$token = $data['token'];
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

        self::$dkPlus['token'] = self::$token;
        $data_update['dkPlus'] = self::$dkPlus;

        update_option(PLUGIN_SLUG, $data_update, 'no');
    }

    private function createToken(): void
    {
        $method = '/token';
        $args = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(self::$dkPlus['login'] . ':' . self::$dkPlus['password']),
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