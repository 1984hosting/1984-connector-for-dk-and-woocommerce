<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Main as Core;
use woo_bookkeeping\App\Core\WP_Notice;

trait API
{
    private static $token = '';
    private static string $api_url = 'https://api.dkplus.is/api/v1';


    public static function getToken()
    {
        static $token = null;

        if (NULL === $token) {
            $settings = Core::getInstance();

            if (!empty($settings[Main::getModuleSlug()]['token'])) {
                $token = $settings[Main::getModuleSlug()]['token'];
            } else {
                self::setToken();
            }
        }

        return $token;
    }

    //todo: check valid token, if isn't valid -> update
    private static function setToken(): void
    {
        $new_settings = Core::getInstance();

        self::createToken($new_settings);

        if (empty(self::$token)) {
            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
            return;
        }


        $new_settings[self::getModuleSlug()]['token'] = self::$token;
        update_option(PLUGIN_SLUG, $new_settings, 'no');
    }

    private static function createToken($data): void
    {
        $method = '/token';
        $args = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($data[self::getModuleSlug()]['login'] . ':' . $data[self::getModuleSlug()]['password']),
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'Description' => 'Woo bookkeeping',
            ],
            'method' => 'POST',
        ];

        $result = self::request($method, $args);

        if (!empty($result['Token'])) {
            self::$token = $result['Token'];
        }
    }


    /**
     * @param string $product_id - get product with dkPlus API
     * @return array
     */
    /*private function productFetchOne(string $product_id): array
    {
        $method = '/Product/' . $product_id; //:code
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$token,
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'method' => 'GET',
        ];

        return $this->request($method, $args);
    }*/

    public function productFetchAll(): array
    {
        $products = $this->request('/Product', $this->setHeaders());
        print_r($products);
        return static::productMap($products);
    }

    public static function productMap($product)
    {
        //todo create template product
        return ProductMap::ProductMap($product);
    }

    private function setHeaders()
    {
        return [
            'headers' => [
                'Authorization' => 'Bearer ' . self::$token,
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'method' => 'GET',
            'timeout' => 30,
        ];
    }

    private static function request(string $method, array $args): array
    {
        $response = wp_remote_request(self::$api_url . $method, $args);
        $body = wp_remote_retrieve_body($response);

        return json_decode($body, true);
    }
}