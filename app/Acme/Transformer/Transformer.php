<?php namespace Acme\Transformer;

abstract class Transformer
{
    /**
     * Transform a collection of items
     *
     * @param $items
     *
     * @return array
     */
    public function transformCollection(array $items)
    {
        return array_map([$this, 'transform'], $items);
    }

    public abstract function transform($item);
}