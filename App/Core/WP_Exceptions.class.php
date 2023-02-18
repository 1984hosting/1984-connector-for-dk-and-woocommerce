<?php
/**
 * The file that defines the WP_Exceptions class
 *
 * A class definition that includes attributes and functions of the WP_Exceptions class
 *
 * @since      0.1
 *
 * @package    WooCoo
 * @subpackage WooCoo/App/Core
 */

namespace woocoo\App\Core;

/**
 * Class WP_Exceptions
 */
class WP_Exceptions extends \Exception
{

    /**
     * Invalid Account
     *
     * @return static
     */
    public static function invalidAccount()
    {
        return new static('Username and/or password incorrect');
    }

    /**
     * Invalid Product
     *
     * @return static
     */
    public static function invalidProduct()
    {
        return new static('Product not found');
    }

    /**
     * Invalid Employee
     *
     * @return static
     */
    public static function invalidEmployee()
    {
        return new static('Employee not found');
    }

    /**
     * Invalid Customer
     *
     * @return static
     */
    public static function invalidCustomer()
    {
        return new static('Customer not found');
    }

    /**
     * Invalid SalesPerson
     *
     * @return static
     */
    public static function invalidSalesPerson()
    {
        return new static('Salesperson not found');
    }

    /**
     * Invalid Invoice
     *
     * @return static
     */
    public static function invalidInvoice()
    {
        return new static('Invoice not found');
    }

    /**
     * Invalid Payment Types
     *
     * @return static
     */
    public static function invalidPaymentTypes()
    {
        return new static('Payment Types not found');
    }

    /**
     * Invalid Response
     *
     * @param $code
     * @return static
     */
    public static function invalidResponse($code)
    {
        return new static('Invalid response, error: ' . $code);
    }
}