<?php

class PortfolioController extends \ApiController {

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

        $trades = Trade::whereUserId($user->id)->orderBy('id', 'desc')->get();

		return $this->respond([
            'data' => $trades,
        ]);
	}

}