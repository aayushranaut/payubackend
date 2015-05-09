<?php

use Acme\Transformer\StocksTransformer;

class StocksController extends \ApiController
{
    /**
     * @var Acme\Transformer\StocksTransformer
     */
    protected $stocksTransformer;

    function __construct(StocksTransformer $stocksTransformer)
    {
        $this->stocksTransformer = $stocksTransformer;
    }

    public function trending()
    {
        $stocks = Stock::orderBy('created_at', 'DESC')->get()->toArray();

        $newList = [];

        foreach ($stocks as $key => $stock) {
            $newList[$stock['name']] = $stock;
        }

        foreach ($newList as $stockName => $stock) {
            $high = DB::table('stock_prices')->where('stock_id', $stock['id'])->max('price');// ->StockPrice::whereStockId($stock['id'])->get();
            $low = DB::table('stock_prices')->where('stock_id', $stock['id'])->min('price');
            $price = DB::table('stock_prices')->where('stock_id', $stock['id'])->orderBy('created_at', 'desc')->first();

            $newList[$stockName]['high'] = $high;
            $newList[$stockName]['low'] = $low;
            $newList[$stockName]['price'] = $price->price;
        }

        return $this->respond([
            'data' => $this->stocksTransformer->transformCollection($newList)
        ]);
    }

    /**
     * Shows a list of individual stocks
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $stock = Stock::whereName($id)->first();

        $high = DB::table('stock_prices')->where('stock_id', $stock['id'])->max('price');// ->StockPrice::whereStockId($stock['id'])->get();
        $low = DB::table('stock_prices')->where('stock_id', $stock['id'])->min('price');


        $stock['high'] = $high;
        $stock['low'] = $low;

        return $this->respond([
            'data'  => $this->stocksTransformer->transform($stock)
        ]);
    }
}