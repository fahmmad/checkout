<?php

/**
 *  Deal Model
 * 
 *  Data is hardcoded for simiplicity of the programming, 
 *   no database connection involved at this stage though the data can be moved on to a database if/when required
 */
namespace Models;

class Deal
{
    /**
     * if we are using db table, this should be split in to multiple tables as there are data redundancy
     */
    const deals = [

        ['sku' => 'A', 'type' => 'multibuy', 'quantity' => 3, 'combined_item' => 0, 'total' => 130],
        ['sku' => 'B', 'type' => 'multibuy', 'quantity' => 2, 'combined_item' => 0, 'total' => 45],
        ['sku' => 'C', 'type' => 'multibuy', 'quantity' => 2, 'combined_item' => 0, 'total' => 38],
        ['sku' => 'C', 'type' => 'multibuy', 'quantity' => 3, 'combined_item' => 0, 'total' => 50],
        ['sku' => 'D', 'type' => 'combined', 'quantity' => 1, 'combined_item' => 'A', 'total' => 5],
    ];

    const deal_types = ['multibuy', 'combined'];

    public function deals()
    {
        return self::deal_types;
    }

    public function find($sku, $type)
    {
        $tempDeals = [];
        $deals = [];
        foreach(self::deals as $deal) {
            if($deal['sku'] == $sku && $deal['type'] == $type) {
                $tempDeals[] = $deal;
            }
        }
        /**
         * Make sure higher counts order first for the deal
         */
        usort($tempDeals, function ($x, $y) {
            return $y['quantity'] - $x['quantity'];
        });

        if(count($tempDeals) > 0) {
            foreach($tempDeals as $deal) {
                $deals[] = new class ($deal) {
                        
                    private $data;

                    public function __construct($deal)
                    {
                        $this->data = $deal;
                    }
                    public function __get($property) {
                        return $this->data[$property] ?? null;
                    }
                };
            }
        }
        return $deals;
    }
}