<?php namespace Acme\Transformer;

class StocksTransformer extends Transformer
{
    public function transform($stock)
    {
        return [
            'id'        => (int)$stock['id'],
            'name'      => $stock['name'],
            'full_name' => $stock['full_name'],
            'price'     => (float)$stock['price'],
            'high'      => (float)$stock['high'],
            'low'       => (float)$stock['low'],
            'graph'     => $stock['graph']
        ];
    }
}