<?php

class UsersController extends \ApiController {

	/**
	 * Display the specified resource.
	 * GET /user/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user = User::whereUsername($id)->first();

        if (!$user)
        {
            $user = User::create([
                'username'  => $id,
                'money'     => 5000.0
            ]);
        }

        //Calculate net-worth

        return $this->respond([
            'data' => $user
        ]);
	}


}