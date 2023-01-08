<?php

namespace woo_bookkeeping\App\Modules\dkPlus;

use woo_bookkeeping\App\Core\Logs;
use woo_bookkeeping\App\Core\Main as Core;
use woo_bookkeeping\App\Core\WP_Exceptions;
use woo_bookkeeping\App\Core\WP_Notice;

trait API
{
    public static $token = '';
    public static string $api_url = 'https://api.dkplus.is/api/v1';


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
            $result = Main::request($method, $args);

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
            $product = Main::request('/Product/' . $product_sku, Main::setHeaders('PUT', $args));

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
            $product = Main::request('/Product/' . $product_sku, Main::setHeaders());

            if (empty($product) || is_bool($product)) {
                throw WP_Exceptions::invalidProduct();
            }

            $result = Main::productMap($product);
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
            $products = Main::request('/Product', Main::setHeaders());

            if (empty($products)) {
                throw WP_Exceptions::invalidProduct();
            }

            $result = [];
            if (is_array($products)) {
                foreach ($products as $product) {
                    $result[] = Main::productMap($product);
                }
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }

        return $result;
    }

    /**
     * Create a new employee
     *
     * @param array $data
     * @return array|bool
     */
    public static function generalEmployeeCreate(array $data): array
    {
        try {
            $employee = Main::request('/general/employee', Main::setHeaders('POST', $data));
            if (empty($employee) || is_bool($employee)) {
                throw WP_Exceptions::invalidEmployee();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
        return $employee;
    }

    /**
     * Get a fetch a specified employee
     *
     * @param string $employee_number
     * @return array|bool
     */
    public static function generalEmployeeFetchOne(string $employee_number): array
    {
        try {
            $employee = Main::request('/general/employee/' . $employee_number, Main::setHeaders());
            if (empty($employee) || is_bool($employee)) {
                throw WP_Exceptions::invalidEmployee();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
            $employee = [];
        }
        return $employee;
    }

    /**
     * Create a new customer
     *
     * @param array $data
     * @return array|bool
     */
    public static function customerCreate(array $data): array
    {
        try {
            $customer = Main::request('/customer', Main::setHeaders('POST', $data));
            if (empty($customer) || is_bool($customer)) {
                throw WP_Exceptions::invalidCustomer();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
            $customer = [];
        }
        return $customer;
    }

    /**
     * Update a specified customer
     *
     * @param string $customer_number
     * @param $args
     * @return array|bool
     */
    public static function customerUpdate(string $customer_number, $args): array
    {
        try {
            $customer = Main::request('/customer/' . $customer_number, Main::setHeaders('PUT', $args));
            if (empty($customer) || is_bool($customer)) {
                throw WP_Exceptions::invalidCustomer();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
        return $customer;
    }

    /**
     * Get a fetch a specified customer
     *
     * @param string $customer_number
     * @return array|bool
     */
    public static function customerFetchOne(string $customer_number): array
    {
        try {
            $customer = Main::request('/customer/' . $customer_number, Main::setHeaders());
            if (empty($customer) || is_bool($customer)) {
                throw WP_Exceptions::invalidCustomer();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
        return $customer;
    }

    /**
     * Get a fetch all customers
     *
     * @param bool $attached_objects
     * @return array
     */
    public static function customerFetchAll(bool $attached_objects = true): array
    {
        try {
            $customers = Main::request('/customer', Main::setHeaders());

            if (empty($customers)) {
                throw WP_Exceptions::invalidCustomer();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
        return $customers;
    }

    /**
     * Search a customer specified by searchstring
     *
     * @param string $search
     * @return array|bool
     */
    public static function customerSearch(string $searchstring): array
    {
        try {
            $customer = Main::request('/customer/search/' . $searchstring, Main::setHeaders());
            if (empty($customer) || is_bool($customer)) {
                throw WP_Exceptions::invalidCustomer();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
            $customer = []; //
        }
        return $customer;
    }

    /**
     * Create a new salesperson
     *
     * @param array $data
     * @return array|bool
     */
    public static function salesPersonCreate(array $data): array
    {
        try {
            $salesperson = Main::request('/sales/person', Main::setHeaders('POST', $data));
            if (empty($salesperson) || is_bool($salesperson)) {
                throw WP_Exceptions::invalidSalesPerson();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
            $salesperson = [];
        }
        return $salesperson;
    }

    /**
     * Update a specified salesperson
     *
     * @param string $salesperson_number
     * @param $args
     * @return array|bool
     */
    public static function salesPersonUpdate(string $salesperson_number, $args): array
    {
        try {
            $salesperson = Main::request('/sales/person/' . $salesperson_number, Main::setHeaders('PUT', $args));
            if (empty($salesperson) || is_bool($salesperson)) {
                throw WP_Exceptions::invalidSalesPerson();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
        return $salesperson;
    }

    /**
     * Get a fetch a specified salesperson
     *
     * @param string $salesperson_number
     * @return array|bool
     */
    public static function salesPersonFetchOne(string $salesperson_number): array
    {
        try {
            $salesperson = Main::request('/sales/person/' . $salesperson_number, Main::setHeaders());
            if (empty($salesperson) || is_bool($salesperson)) {
                throw WP_Exceptions::invalidSalesPerson();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
            $salesperson = [];
        }
        return $salesperson;
    }

    /**
     * Get a fetch all salespersons
     *
     * @param int $page
     * @param int $count
     * @return array
     */
    public static function salesPersonFetchAll(int $page = 1, int $count = 100): array
    {
        try {
            $salespersons = Main::request('/sales/person/page/' . $page . '/' . $count, Main::setHeaders());
            if (empty($salespersons)) {
                throw WP_Exceptions::invalidSalesPerson();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
        return $salespersons;
    }

    /**
     * Create a new sale invoice
     *
     * @param $data
     * @return array|bool
     */
    public static function salesCreateInvoice($data): array
    {
        try {
            $invoice = Main::request('/sales/invoice', Main::setHeaders('POST', $data));
            if (empty($invoice) || is_bool($invoice)) {
                throw WP_Exceptions::invalidInvoice();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
            $invoice = [];
        }
        return $invoice;
    }

    /**
     * Get a fetch a specified invoice
     *
     * @param string $invoice_number
     * @return array|bool
     */
    public static function salesInvoiceFetchOne(string $invoice_number): array
    {
        try {
            $invoice = Main::request('/sales/invoice/' . $invoice_number, Main::setHeaders());
            if (empty($invoice) || is_bool($invoice)) {
                throw WP_Exceptions::invalidInvoice();
            }
        } catch (WP_Exceptions $e) {
            Logs::appendLog(Main::$module_slug . '/logs', $e->getMessage());
        }
        return $invoice;
    }

    /**
     * getting one a product with dkplus API
     * @return array
     */
    public static function productMap($product)
    {
        return ProductMap::ProductMap($product);
    }

    public static function setHeaders($method = 'GET', $body = [])
    {
        return [
            'body' => $body,
            'headers' => [
                'Authorization' => 'Bearer ' . Main::getToken(),
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'method' => $method,
            'timeout' => 30,
        ];
    }

    public static function request(string $method, array $args)
    {
        try {
            $args['timeout'] = 300;
            $request = wp_remote_request(Main::$api_url . $method, $args);
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