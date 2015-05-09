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

		return $this->respond([
            'data' => $this->portfolioTransformer->transformCollection($trades),
        ]);
	}

}