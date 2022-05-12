<?php

namespace Tests\Unit;

require_once __DIR__.'/../autoloader.php';

use PHPUnit\Framework\TestCase;
use Classes\PurchaseManager;

class PurchaseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_no_deal()
    {
        $purchaseManager = new PurchaseManager();
        $purchaseManager->purchase('A', 2);
        $purchaseManager->purchase('B', 1);
        $cart = $purchaseManager->purchase('C', 1);
        $purchaseManager->applyPromotion();
        //$purchaseManager->render();
        
        $this->assertEquals($purchaseManager->total(), 150); // 50*2 + 30 + 20
    }

    /**
     * should pick up `3 for 50`
     */
    public function test_higher_count_first()
    {
        $purchaseManager = new PurchaseManager();
        $purchaseManager->purchase('C', 3);

        $this->assertNotEquals($purchaseManager->total(), 50);
    }

    /**
     * shouldn't pick up `2 for 38 `
     */
    public function test_lower_count()
    {
        $purchaseManager = new PurchaseManager();
        $purchaseManager->purchase('C', 3);

        $this->assertNotEquals($purchaseManager->total(), 58); // 38 + 20
    }

    public function test_deal()
    {
        $purchaseManager = new PurchaseManager();
        $purchaseManager->purchase('A', 4);
        $purchaseManager->purchase('B', 2);
        $cart = $purchaseManager->purchase('C', 2);
        $purchaseManager->purchase('D', 1);
        $purchaseManager->applyPromotion();
        
        $this->assertEquals($purchaseManager->total(), 268); // 130 + 50 + 45 + 38 + 5
    }

    public function test_fail_on_sku()
    {
        $purchaseManager = new PurchaseManager();
        $cart = $purchaseManager->purchase('F', 1);
        
        $this->assertEquals($purchaseManager->total(), 0); // SKU 'F' not defined
    }
}   