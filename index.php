<?php
use Classes\PurchaseManager;

require_once './autoloader.php';

$purchaseManager = new PurchaseManager();

$purchaseManager->purchase('A', 2);
$purchaseManager->purchase('D', 1);
$purchaseManager->applyPromotion();

$purchaseManager->render();

echo "Total:".$purchaseManager->total()."\n";

