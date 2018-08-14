<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{

    /*
     * @attribute \illuminate\database\Eloquent\Collection $productsCollection
     */
    private $productsCollection;

    /*
     * @attribute string $productsJson
     */
    private $productsJson = '[
        {
            "id": "A101",
            "description": "Screwdriver",
            "category": "1",
            "price": "9.75"
        },
        {
            "id": "A102",
            "description": "Electric screwdriver",
            "category": "1",
            "price": "49.50"
        },
        {
            "id": "B101",
            "description": "Basic on-off switch",
            "category": "2",
            "price": "4.99"
        },
        {
            "id": "B102",
            "description": "Press button",
            "category": "2",
            "price": "4.99"
        },
        {
            "id": "B103",
            "description": "Switch with motion detector",
            "category": "2",
            "price": "12.95"
        }
    ]';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->productsCollection = collect(json_decode($this->productsJson, true));
    }


    /**
     * Returns the product information
     *
     * @param string $productId
     * @return JsonResponse
     */
    public function getProduct(string $productId) : JsonResponse {
        $product = $this->productsCollection->firstWhere('id', $productId);
        if (!$product) {
            return new JsonResponse(null, 404);
        }

        return new JsonResponse($product, 200);
    }
}
