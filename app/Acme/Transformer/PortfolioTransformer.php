<?php namespace Acme\Transformer;

class PortfolioTransformer extends Transformer
{

    public function transform($item)
    {
        return [
            'id'         => (int)$item['id'],
            'user_id'    => (int)$item['user_id'],
            "stock_id"   => (int)$item['stock_id'],
            "name"       => $item['name'],
            "buy_price"  => (float)$item['buy_price'],
            'profit'     => (float)$item['profit'],
            'quantity'   => (int)$item['quantity'],
            'price'      => (float)$item['price'],
            "sold"       => (boolean)$item['sold'],
            "created_at" => $item['created_at'],
            "updated_at" => $item['updated_at']
        ];
    }
}