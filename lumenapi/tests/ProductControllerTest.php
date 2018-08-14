<?php

class ProductControllerTest extends TestCase
{
    /**
     * Test to return existing product
     *
     * @test
     */
    public function verifyIfProductExists()
    {
        $productController = new \App\Http\Controllers\ProductController();
        $productIdToVerify = "A101";

        $productResponse = $productController->getProduct($productIdToVerify);

        $product = json_decode($productResponse->content());

        $this->assertEquals(200, $productResponse->getStatusCode());
        $this->assertEquals($productIdToVerify, $product->id);
    }

    /**
     * Test to return existing product
     *
     * @test
     */
    public function verifyIfProductNotExists()
    {
        $productController = new \App\Http\Controllers\ProductController();
        $productIdToVerify = "C444";

        $productResponse = $productController->getProduct($productIdToVerify);

        $this->assertEquals(404, $productResponse->getStatusCode());
    }
}
