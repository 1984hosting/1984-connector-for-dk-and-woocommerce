<?php

namespace woo_bookkeeping\App\Core;

class WP_Exceptions extends \Exception
{
    public static function invalidAccount()
    {
        return new static('Username and/or password incorrect');
    }
    public static function invalidProduct()
    {
        return new static('Product not found');
    }
    public static function invalidEmployee()
    {
        return new static('Employee not found');
    }
    public static function invalidCustomer()
    {
        return new static('Customer not found');
    }
    public static function invalidSalesPerson()
    {
        return new static('Salesperson not found');
    }
    public static function invalidInvoice()
    {
        return new static('Invoice not found');
    }
    public static function invalidPaymentTypes()
    {
        return new static('Payment Types not found');
    }
    public static function invalidResponse($code)
    {
        return new static('Invalid response, error: ' . $code);
    }
}