<?php

class DiscountControllerTest extends TestCase
{
    /**
     * Test two or more tools discount
     *
     * @test
     */
    public function getTwoOrMoreToolsCheapestDiscount()
    {
        $orderContent = json_decode('{
          "id": "3",
          "customer-id": "3",
          "items": [
            {
              "product-id": "A101",
              "quantity": "2",
              "unit-price": "9.75",
              "total": "19.50"
            },
            {
              "product-id": "A102",
              "quantity": "1",
              "unit-price": "49.50",
              "total": "49.50"
            }
          ],
          "total": "69.00"
        }');
        $order = collect($orderContent);

        $discount = new \App\Business\Discount($order);
        $mockAppliedDiscount = $this->getMockBuilder(\App\AppliedDiscount::class)->getMock();

        $discountedOrder = json_decode($discount->applyDiscounts($mockAppliedDiscount), true);
        $appliedDiscount = 3.9;
        $total = 65.1;
        $this->assertEquals($appliedDiscount, $discountedOrder['applied-discount']);
        $this->assertEquals($total, $discountedOrder['total']);
    }

    /**
     * Test five switches discount
     *
     * @test
     */
    public function getFiveSwitchesDiscount()
    {
        $orderContent = json_decode('{
          "id": "1",
          "customer-id": "1",
          "items": [
            {
              "product-id": "B102",
              "quantity": "10",
              "unit-price": "4.99",
              "total": "49.90"
            }
          ],
          "total": "49.90"
        }');
        $order = collect($orderContent);

        $discount = new \App\Business\Discount($order);
        $mockAppliedDiscount = $this->getMockBuilder(\App\AppliedDiscount::class)->getMock();

        $discountedOrder = json_decode($discount->applyDiscounts($mockAppliedDiscount), true);
        $appliedDiscount = 4.99;
        $total = 49.90;
        $this->assertEquals($appliedDiscount, $discountedOrder['applied-discount']);
        $this->assertEquals($total, $discountedOrder['total']);
    }

    /**
     * Test revenue discount
     *
     * @test
     */
    public function getRevenueDiscount()
    {
        $orderContent = json_decode('{
          "id": "2",
          "customer-id": "2",
          "items": [
            {
              "product-id": "B102",
              "quantity": "5",
              "unit-price": "4.99",
              "total": "24.95"
            }
          ],
          "total": "24.95"
        }');
        $order = collect($orderContent);

        $discount = new \App\Business\Discount($order);
        $mockAppliedDiscount = $this->getMockBuilder(\App\AppliedDiscount::class)->getMock();

        $discountedOrder = json_decode($discount->applyDiscounts($mockAppliedDiscount), true);
        $appliedDiscount = 2.5;
        $total = 22.45;
        $this->assertEquals($appliedDiscount, $discountedOrder['applied-discount']);
        $this->assertEquals($total, $discountedOrder['total']);
    }

    /**
     * Test all three kinds of discount applied
     *
     * @test
     */
    public function getAllThreeDiscounts()
    {
        $orderContent = json_decode('{
          "id": "1",
          "customer-id": "2",
          "items": [
            {
              "product-id": "B102",
              "quantity": "10",
              "unit-price": "4.99",
              "total": "49.90"
            },
            {
              "product-id": "B102",
              "quantity": "5",
              "unit-price": "4.99",
              "total": "24.95"
            },
                {
              "product-id": "A101",
              "quantity": "2",
              "unit-price": "9.75",
              "total": "19.50"
            },
            {
              "product-id": "A102",
              "quantity": "1",
              "unit-price": "49.50",
              "total": "49.50"
            }
          ],
          "total": "143.85"
        }');
        $order = collect($orderContent);

        $discount = new \App\Business\Discount($order);
        $mockAppliedDiscount = $this->getMockBuilder(\App\AppliedDiscount::class)->getMock();

        $discountedOrder = json_decode($discount->applyDiscounts($mockAppliedDiscount), true);
        $appliedDiscount = 13.39;
        $total = 120.48;
        $this->assertEquals($appliedDiscount, $discountedOrder['applied-discount']);
        $this->assertEquals($total, $discountedOrder['total']);
    }
}
