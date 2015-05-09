<?php namespace Acme\Transformer;

class UserTransformer extends Transformer {

    public function transform($user)
    {
        return [
            'id'    => $user['id'],
            'username' => $user['username'],
            'money'     => (int)$user['money'],
        ];
    }
}