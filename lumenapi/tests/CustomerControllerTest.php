<?php

class CustomerControllerTest extends TestCase
{
    /**
     * Test to return existing user
     *
     * @test
     */
    public function verifyIfUserExists()
    {
        $customerController = new \App\Http\Controllers\CustomerController();
        $customerIdToVerify = 1;

        $customerResponse = $customerController->getCustomer($customerIdToVerify);

        $customer = json_decode($customerResponse->content());

        $this->assertEquals(200, $customerResponse->getStatusCode());
        $this->assertEquals($customerIdToVerify, $customer->id);
    }

    /**
     * Test to return existing user
     *
     * @test
     */
    public function verifyIfUserNotExists()
    {
        $customerController = new \App\Http\Controllers\CustomerController();
        $customerIdToVerify = 5;

        $customerResponse = $customerController->getCustomer($customerIdToVerify);

        $this->assertEquals(404, $customerResponse->getStatusCode());
    }
}
