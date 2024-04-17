<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Logs;
use woo_bookkeeping\App\Core\Main as Core;
use woo_bookkeeping\App\Core\WP_Exceptions;
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

    private static function setToken(): void
    {
        $new_settings = Core::getInstance();

        self::createToken($new_settings);

        if (empty(self::$token)) {
            if (!isset($_GET['page']) || $_GET['page'] !== PLUGIN_SLUG) return;

            Logs::appendLog(Main::$module_slug . '/logs', 'Error: Please, check the correctness of the login and password.');
            new WP_Notice('error', 'Error: Please, check the correctness of the login and password.');
            return;
        }

        Logs::appendLog(Main::$module_slug . '/logs', 'Token successfully received');

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

        try {
            $result = static::request($method, $args);

            if (empty($result['Token'])) {
                throw WP_Exceptions::invalidAccount();
            }

            self::$token = $result['Token'];
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
    }

    /**
     * Send data to dkPlus
     * @param string $product_sku
     * @return array
     */
    public static function productUpdateDK(string $product_sku, $args): bool
    {
        try {
            $product = static::request('/Product/' . $product_sku, static::setHeaders('PUT', $args));

            if (empty($product)) {
                throw WP_Exceptions::invalidProduct();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }

        return true;
    }

    /**
     * getting a product with a dkplus API
     * @param string $product_sku
     * @return array Product data
     */
    public static function productFetchOne(string $product_sku): array
    {
        try {
            $product = static::request('/Product/' . $product_sku, static::setHeaders());

            if (empty($product) || is_bool($product)) {
                throw WP_Exceptions::invalidProduct();
            }

            $result = static::productMap($product);
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }

        return $result;
    }

    /**
     * getting all products with dkplus API
     * @return array Products
     */
    public static function productFetchAll(): array
    {
        try {
            $products = static::request('/Product', static::setHeaders());

            if (empty($products)) {
                throw WP_Exceptions::invalidProduct();
            }

            $result = [];
            if (is_array($products)) {
                foreach ($products as $product) {
                    $result[] = static::productMap($product);
                }
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }

        return $result;
    }

    /**
     * getting one a product with dkplus API
     * @return array
     */
    public static function productMap($product)
    {
        return ProductMap::ProductMap($product);
    }

    private static function setHeaders($method = 'GET', $body = [])
    {
        return [
            'body' => $body,
            'headers' => [
                'Authorization' => 'Bearer ' . static::getToken(),
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'method' => $method,
            'timeout' => 30,
        ];
    }

    private static function request(string $method, array $args)
    {
        try {
            $args['timeout'] = 300;
            $request = wp_remote_request(static::$api_url . $method, $args);
            $response_code = wp_remote_retrieve_response_code($request);

            if ($response_code != 200) {
                throw WP_Exceptions::invalidResponse($response_code);
            }

            $body = wp_remote_retrieve_body($request);
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }

        return !empty($body) ? json_decode($body, true) : true;
    }
}