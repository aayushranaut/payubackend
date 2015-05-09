<?php

use Acme\Transformer\LeaderboardTransformer;

class LeaderboardController extends \ApiController {

    /**
     * @var Acme\Transformer\LeaderboardTransformer
     */
    protected $leaderboardTransformer;

    function __construct(LeaderboardTransformer $leaderboardTransformer)
    {
        $this->leaderboardTransformer = $leaderboardTransformer;
    }

    /**
     * Returns the leaderboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function net()
    {
        $net_worth = Trade::from('trades')
            ->leftJoin('users', 'trades.user_id', '=', 'users.id')
            ->select(DB::raw('user_id, username, avatar_url, sum(quantity * buy_price) + money as net'))
            ->where('sold', '0')
            ->groupBy('user_id')
            ->orderBy('net', 'desc')
            ->get()->toArray();

        $leaderboard = [];
        return $this->respond([
            'data'  => $this->leaderboardTransformer->transformCollection($net_worth)
        ]);
    }

    public function profit()
    {
        $stocks = Stock::orderBy('created_at', 'DESC')->get()->toArray();
        $newList = [];
        foreach ($stocks as $key => $stock) {
            $newList[$stock['id']] = $stock;
        }
        $users = User::all()->toArray();

        foreach ($newList as $stockName => $stock) {
            $price = DB::table('stock_prices')->where('stock_id', $stock['id'])->orderBy('created_at', 'desc')->first();

            $newList[$stockName]['price'] = $price->price;
        }

        foreach($users as &$user)
        {
            $user['profit'] = 0;
            $trades = Trade::whereUserId($user['id'])->get();

            foreach($trades as $trade)
            {
                $user['profit'] += ($newList[$trade['stock_id']]['price'] - $trade['buy_price']) * $trade['quantity'];
            }
        }

        return $this->respond([
            'data'  => $users
        ]);
    }
}