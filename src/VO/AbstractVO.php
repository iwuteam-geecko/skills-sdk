<?php


namespace Geecko\Skills\VO;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractVO
{
    public $id;

    public function toArray()
    {
        $data = get_object_vars($this);
        return array_filter($data, function ($val) {
            return $val !== null;
        });
    }
}
