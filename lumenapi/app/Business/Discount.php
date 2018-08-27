<?php
/**
 * Created by PhpStorm.
 * User: rodrigo
 * Date: 14-08-2018
 * Time: 12:06
 */

namespace App\Business;

use App\AppliedDiscount;
use Illuminate\Support\Collection;
use GuzzleHttp;

// @TODO Technical debt, move discount parameters structure to a database (not necessary for this few discounts rules)
const REVENUE_DISCOUNT = 0.1;
const MINIMUM_REVENUE_VALUE_FOR_DISCOUNT = 1000;
const MINIMUM_SWITCH_CATEGORY_PRODUCT = 5;
const SWITCH_CATEGORY = 2;
const SWITCH_CATEGORY_FREE_QUANTITY_PRODUCT = 1;
const TOOLS_CATEGORY = 1;
const MINIMUM_TOOLS_CATEGORY_PRODUCT = 2;
const TOOLS_CATEGORY_DISCOUNT = 0.2;

class Discount
{

    private $order;

    /**
     * Discount constructor.
     *
     * @param Collection $order
     */
    public function __construct(Collection &$order)
    {
        $this->order = $order;
    }

    /**
     * @param AppliedDiscount $appliedDiscount
     * @return Collection
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function applyDiscounts(AppliedDiscount $appliedDiscount) : Collection
    {
        try {
            /**
             * According to the test description there is no rule about the customer get just one or more discounts,
             * then I am applying all discounts to an order.
             */
            $this->getTwoOrMoreToolsCheapestDiscount(ApiFactory::build('product'), $appliedDiscount);
            $this->getFiveSwitchesDiscount(ApiFactory::build('product'), $appliedDiscount);
            $this->getRevenueDiscount(ApiFactory::build('customer'), $appliedDiscount);
        } catch (\Exception $e) {
            throw $e;
        }

        return $this->order;
    }

    /**
     * method that returns discount by revenue major than 1000
     *
     * @param GuzzleHttp\Client $client
     * @param AppliedDiscount $appliedDiscount
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    private function getRevenueDiscount(GuzzleHttp\Client $client, AppliedDiscount $appliedDiscount)
    {
        try {
            // simulation of request to a customer micro service
            $response = $client->request('get', 'getcustomer/' . $this->order['customer-id']);
            $body = $response->getBody();

            $customer = collect(json_decode($body, true));

            if ($customer['revenue'] > MINIMUM_REVENUE_VALUE_FOR_DISCOUNT) {
                $discount = round($this->order['total'] * REVENUE_DISCOUNT, 2);
                $discountedValue = round($this->order['total'] - $discount, 2);
                $this->order['applied-discount'] = $discount;
                $this->order['total'] = $discountedValue;

                $this->saveDiscount($appliedDiscount, $this->order['id'], $this->order['customer-id'], 'REVENUE',
                    $discount);
            }
        } catch (GuzzleHttp\Exception\RequestException $e) {
            throw $e;
        }
    }


    /**
     * method that returns discount for more than 5 switches bought
     *
     * this function gets all order items and return the products data to verify applicable discounts
     * this also represents a method that is used to solve a technical debt from product micro service that
     * doesn't has an endpoint to return a product list by an group of products ids
     *
     * @param GuzzleHttp\Client $client
     * @param AppliedDiscount $appliedDiscount
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    private function getFiveSwitchesDiscount(GuzzleHttp\Client $client, AppliedDiscount $appliedDiscount)
    {
        try {
            $items = json_decode(json_encode($this->order['items']), true);
            $discountTotal = 0;
            foreach ($items as $key => &$value) {
                // simulation of request to a products micro-service
                $response = $client->request('get', 'getproduct/' . $value['product-id']);
                $body = $response->getBody();
                $product = json_decode($body, true);
                if ($product['category'] == SWITCH_CATEGORY &&
                    (int) $value['quantity' ] >= MINIMUM_SWITCH_CATEGORY_PRODUCT) {
                    $value['quantity'] += SWITCH_CATEGORY_FREE_QUANTITY_PRODUCT;
                    $discountTotal += $product['price'];
                }
            }
            if ($discountTotal > 0) {
                $this->order['items'] = $items;
                $this->order['applied-discount'] = $discountTotal;
                // save applied discount
                $this->saveDiscount($appliedDiscount, $this->order['id'], $this->order['customer-id'], 'SWITCH',
                    $discountTotal);
            }
        } catch (GuzzleHttp\Exception\RequestException $e) {
            throw $e;
        }
    }

    /**
     * @param GuzzleHttp\Client $client
     * @param AppliedDiscount $appliedDiscount
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    private function getTwoOrMoreToolsCheapestDiscount(GuzzleHttp\Client $client, AppliedDiscount $appliedDiscount)
    {
        try {
            $items = json_decode(json_encode($this->order['items']), true);
            $countToolsProducts = 0;
            $cheapestProduct = null;
            foreach ($items as $key => &$value) {
                // simulation of request to a products micro-service
                $response = $client->request('get', 'getproduct/' . $value['product-id']);
                $body = $response->getBody();
                $product = json_decode($body, true);
                if ($product['category'] == TOOLS_CATEGORY) {
                    $countToolsProducts++;
                    if (!is_null($cheapestProduct)) {
                        if ($product['price'] < $cheapestProduct['price']) {
                            $cheapestProduct = $product;
                        }
                    } else {
                        $cheapestProduct = $product;
                    }
                }
            }
            if ($countToolsProducts >= MINIMUM_TOOLS_CATEGORY_PRODUCT) {
                $productKey = array_search($cheapestProduct['id'], $items);
                $discount = round($items[$productKey]['total'] * TOOLS_CATEGORY_DISCOUNT, 2);
                $discountedValue = round($items[$productKey]['total'] - $discount, 2);

                $items[$productKey]['total'] = $discountedValue;

                $this->order['applied-discount'] = $discount;
                $this->order['total'] =  $this->order['total'] - $discount;
                $this->order['items'] = $items;
                // save applied discount
                $this->saveDiscount($appliedDiscount, $this->order['id'], $this->order['customer-id'], 'TOOLS',
                    $discount);
            }

        } catch (GuzzleHttp\Exception\RequestException $e) {
            throw $e;
        }
    }

    private function saveDiscount(AppliedDiscount $appliedDiscount, int $orderId, int $customerId, string $type,
                                  float $discount) {
        $appliedDiscount->order_id = $orderId;
        $appliedDiscount->customer_id = $customerId;
        $appliedDiscount->type = $type;
        $appliedDiscount->discount = $discount;
        $appliedDiscount->save();
    }

}