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
    public static function invalidResponse($code)
    {
        return new static('Invalid response, error: ' . $code);
    }
}