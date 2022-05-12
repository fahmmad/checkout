<?php

/**
 *  Item Model
 * 
 *  Data is hardcoded for simiplicity of the programming, 
 *   no database connection involved at this stage though the data can be moved on to a database if/when required
 */
namespace Models;

class Item
{
    const items = [
        /* field sku is redundant here, but let be there for the sake of it, in a db world you will have id, sku, etc*/
        'A' => ['sku' =>  'A',  'unit_price' => '50' ],
        'B' => ['sku' =>  'B',  'unit_price' => '30' ],
        'C' => ['sku' =>  'C',  'unit_price' => '20' ],
        'D' => ['sku' =>  'D',  'unit_price' => '15' ],
        'D' => ['sku' =>  'E',  'unit_price' => '5' ],
    ];

    public function find($sku)
    {
        if(isset(self::items[$sku])) {

            return new class (self::items[$sku]) {
                
                private $data;

                public function __construct($item)
                {
                    $this->data = $item;
                }
                public function __get($property) {
                    return $this->data[$property] ?? null;
                }
            };
        }
        return null;
    }
}