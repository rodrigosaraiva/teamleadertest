<?php

namespace App\Http\Controllers;

use App\AppliedDiscount;
use App\Business\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * @param Request $orderRequest
     * @return JsonResponse
     */
    public function getDiscount(Request $orderRequest)
    {
        try {
            $orderContent = json_decode($orderRequest->getContent());
            $order = collect($orderContent);

            $discount = new Discount($order);
            $discountedOrder = $discount->applyDiscounts(new AppliedDiscount());
        } catch (\Exception $e) {
            return new JsonResponse($e->getMessage(), $e->getCode());
        }

        return new JsonResponse($discountedOrder, 200);
    }
}
