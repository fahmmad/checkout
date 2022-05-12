<?php
/**
 * A class to handle the purchase, maintains cart. Also maintains a promoted_cart if any promotion applied.
 * 
 * Todo: Some methods in this class are against SOLID principle, I know, but we have time limitation
 */
namespace Classes;

use Models\Item;
use Models\Deal;

class PurchaseManager
{
    private $cart = [];
    private $cart_with_promotion = [];

    public function purchase($sku, $quantity )
    {
        if(Item::find($sku) ) {
            if(isset($this->cart[$sku])) {
                $this->cart[$sku] += $quantity;
            } else {
                $this->cart[$sku] = $quantity;
            }
        }
        return $this->cart;
    }

    public function applyPromotion()
    {
        foreach($this->cart as $sku => $quantity) {
            
            foreach(Deal::deals() as $deal) {
                $deals = Deal::find($sku, $deal);
            
                if(count($deals)<=0) { 
                    // no deal exists for this sku
                    continue;
                }
                
                foreach($deals as $dealToApply) {
                    $this->applyDeal($sku, $dealToApply);
                }
            }
        }
    }

    /**
     * Todo: against SOLID principles(due to the time limitation)
     */
    function applyDeal($sku, $deal)
    {
        /* does it meet the minimum quantity forthe deal */
        if($deal->quantity && $this->cart[$sku] < $deal->quantity) {
            // just to be sure
            return;
        }
        if($deal->type == 'combined' && !isset($this->cart[$deal->combined_item]))
        {
            return;
        }
        
        $this->cart[$sku] -= $deal->quantity;
        // move item to promoted list
        $this->cart_with_promotion[$sku][] = ['quantity' => $deal->quantity, 'price' => $deal->total]; // deal got a total already specified, so just assign it directly

        if($deal->type == 'combined') {
            $itemCombined = Item::find($deal->combined_item);
            $this->cart[$deal->combined_item] --;

            // move combined item to promoted list
            $this->cart_with_promotion[$deal->combined_item][] = ['quantity' => $deal->quantity, 'price' => $itemCombined->unit_price * $deal->quantity];
        }
    }

    function total()
    {
        $total = 0;
        foreach($this->cart as $sku => $quantity) {
            $item = Item::find($sku);
            $total += ($item->unit_price * $quantity);
        }
        foreach($this->cart_with_promotion as $list) {
            if(is_array($list)) {
                foreach($list as $promoted) {
                    $total += $promoted['price'];
                }
            }
        }
        return $total;
    }

    public function render()
    {
        echo "\nShopping Cart!\n";
        foreach($this->cart as $sku => $quantity) {
            if($quantity) {
                $item = Item::find($sku);
                echo "{$sku}\t{$quantity}\t".($item->unit_price * $quantity)."\n";
            }
        }
        echo "Promotion sale!\n";
        foreach($this->cart_with_promotion as $sku => $list) {
            if(is_array($list)) {
                foreach($list as $promoted) {
                    echo "{$sku}\t" . $promoted['quantity'] . "\t" . $promoted['price']."\n";
                }
            }
        }
    }
}
