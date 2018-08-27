<?php

class ApiFactoryTest extends TestCase
{
    /**
     * Test to return existing product
     *
     * @test
     */
    public function checkReturnApiCustomer()
    {
        $apiCustomer = \App\Business\ApiFactory::build('customer');
        $apiConfig = $apiCustomer->getConfig();

        $this->assertInstanceOf('GuzzleHttp\Client', $apiCustomer);
        $this->assertEquals('http://172.17.0.4/customer/', $apiConfig['base_uri']->__toString());
    }

    /**
     * Test to return existing product
     *
     * @test
     */
    public function checkReturnApiProduct()
    {
        $apiProduct = \App\Business\ApiFactory::build('product');
        $apiConfig = $apiProduct->getConfig();

        $this->assertInstanceOf('GuzzleHttp\Client', $apiProduct);
        $this->assertEquals('http://172.17.0.4/product/', $apiConfig['base_uri']->__toString());
    }

    /**
     * Test to return existing product
     *
     * @test
     */
    public function checkReturnExcpetion()
    {
        try {
            $apiException = \App\Business\ApiFactory::build('invalidapi');
            $this->fail("Expected Exception has not been raised.");
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), "Invalid Micro Service Api Type");
        }
    }
}
