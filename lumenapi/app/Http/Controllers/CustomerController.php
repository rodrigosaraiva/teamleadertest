<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{

    /*
     * @attribute \illuminate\database\Eloquent\Collection $customersCollection
     */
    private $customersCollection;

    /*
     * @attribute string $customersJson
     */
    private $customersJson = '[
            {
                "id": "1",
                "name": "Coca Cola",
                "since": "2014-06-28",
                "revenue": "492.12"
            },
            {
                "id": "2",
                "name": "Teamleader",
                "since": "2015-01-15",
                "revenue": "1505.95"
            },
            {
                "id": "3",
                "name": "Jeroen De Wit",
                "since": "2016-02-11",
                "revenue": "0.00"
            }
        ]';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->customersCollection = collect(json_decode($this->customersJson, true));
    }


    /**
     * Returns the customer information
     *
     * @param string $customerId
     * @return JsonResponse
     */
    public function getCustomer(string $customerId) : JsonResponse {
        $customer = $this->customersCollection->firstWhere('id', $customerId);
        if (!$customer) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse($customer, 200);
    }
}
