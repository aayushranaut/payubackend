<?php

class CronController extends \ApiController
{
    public function manipulate()
    {
        $stocks = Stock::all();

        foreach ($stocks as $stock)
        {
            $stockInfo = StockPrice::whereStockId($stock['id'])->orderBy('stock_id', 'desc')->first();

            $newStock = $stockInfo->replicate();
            $random = rand(0, 1);

            if($random == 0)
            {
                $newStock['price'] += mt_rand(1, 10);
            }
            else
            {
                $newStock['price'] -= mt_rand(1, 10);
            }

            $newStock->save();
        }

        return $this->respond([
            'Success'
        ]);
    }
}