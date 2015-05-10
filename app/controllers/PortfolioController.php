<?php

use Acme\Transformer\PortfolioTransformer;

class PortfolioController extends \ApiController {

    /**
     * @var Acme\Transformer\PortfolioTransformer
     */
    protected $portfolioTransformer;

    function __construct(PortfolioTransformer $portfolioTransformer)
    {
        $this->portfolioTransformer = $portfolioTransformer;
    }

    /**
	 * Display the specified resource.
	 * GET /portfolio/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        $user = User::whereUsername($id)->first();

        if (!$user) {
            return $this->respondNotFound("Could not find user");
        }

        $trades = Trade::whereUserId($user->id)->orderBy('id', 'desc')->get()->toArray();

        foreach($trades as &$trade)
        {
            $stockPrice = StockPrice::whereStockId($trade['stock_id'])->orderBy('created_at', 'desc')->first();
            $stock = Stock::whereId($trade['stock_id'])->first();

            $trade['name'] = $stock->name;
            $trade['price'] = $stockPrice['price'];
            $trade['profit'] = $stockPrice['price'] - $trade['buy_price'];
        }

		return $this->respond([
            'data' => $this->portfolioTransformer->transformCollection($trades),
        ]);
	}

}