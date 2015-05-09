<?php namespace Acme\Transformer;

class LeaderboardTransformer extends Transformer {

    public function transform($item)
    {
        return [
            'user_id'   => (int) $item['user_id'],
            'username'  => $item['username'],
            'avatar_url'=> $item['avatar_url'],
            'net_worth' => (float) $item['net'],

        ];
    }
}