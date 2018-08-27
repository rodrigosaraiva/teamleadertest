<?php
/**
 * Created by PhpStorm.
 * User: rodrigo
 * Date: 27-08-2018
 * Time: 9:53
 */

namespace App\Business;

use GuzzleHttp;
use Exception;

/**
 * Class ApiFactory
 * @package App\Business
 *
 * This class simulate to create an API connection to use external Micro Services
 * It is also used to demonstrate a simple Factory Design Pattern
 */
class ApiFactory
{
    /**
     * @param string $type Should be customer|product
     * @return GuzzleHttp\Client
     * @throws Exception
     */
    public static function build(string $type) : GuzzleHttp\Client
    {
        $apiUrl = "";
        switch ($type) {
            case 'customer':
                $apiUrl = "http://172.17.0.4/customer/";
                break;
            case 'product':
                $apiUrl = "http://172.17.0.4/product/";
                break;
            default:
                throw new Exception('Invalid Micro Service Api Type');

        }
        return new GuzzleHttp\Client(['base_uri' => $apiUrl]);
    }
}