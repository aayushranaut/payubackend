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
        $stocks = Stock::orderBy('created_at', 'ASC')->get()->toArray();

        $newList = [];

        foreach ($stocks as $key => $stock) {
            $newList[$stock['name']] = $stock;
        }

        foreach ($newList as $stockName => $stock) {
            $high = DB::table('stock_prices')->where('stock_id', $stock['id'])->max('price');// ->StockPrice::whereStockId($stock['id'])->get();
            $low = DB::table('stock_prices')->where('stock_id', $stock['id'])->min('price');
            $price = DB::table('stock_prices')->where('stock_id', $stock['id'])->orderBy('created_at', 'desc')->first();

            $graph = DB::table('stock_prices')->where('stock_id', $stock['id'])->orderBy('created_at', 'asc')->limit(10)->get();

            $newList[$stockName]['high'] = $high;
            $newList[$stockName]['low'] = $low;
            $newList[$stockName]['price'] = $price->price;
            $newList[$stockName]['graph'] = $graph;
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

    public function buy($username, $stockName, $quantity)
    {
        $user = User::whereUsername($username)->first();
        $stock = Stock::whereName($stockName)->first();
        $stockPrice = StockPrice::whereStockId($stock->id)->orderBy('created_at', 'desc')->first();

        if($user->money > $stockPrice->price * $quantity) {
            $trade = Trade::create([
                'user_id'   => $user->id,
                'stock_id'  => $stock->id,
                'buy_price' => $stockPrice->price,
                'quantity'  => $quantity
            ]);

            $user->money -= $stockPrice->price * $quantity;
            $user->save();

            return $this->respond([
                "Bought {$quantity} shares in {$stockName}"
            ]);
        }
        else {
            return $this->respondWithError("Insufficient balance");
        }
    }

    public function sell($username, $tradeId)
    {
        $user = User::whereUsername($username)->first();
        $trade = Trade::whereId($tradeId)->where('user_id', '=', $user->id)->first();
        $stock = Stock::whereId($trade->stock_id)->first();
        $stockPrice = StockPrice::whereStockId($stock->id)->orderBy('created_at', 'desc')->first();

        $trade->sold = 1;
        $trade->save();

        $user->money += $trade->quantity * $stockPrice->price;
        $user->save();

        return $this->respond([
            "Sold {$trade->quantity } shares of {$stock->name}"
        ]);
    }
}