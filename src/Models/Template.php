<?php


namespace Geecko\Skills\Models;


use Geecko\Skills\VO\TemplateVO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Template extends Model
{
    use SoftDeletes;

    protected $table = 'skillservice_templates';
    protected $guarded = [];
    public $timestamps = false;

    public function saveFromVO(TemplateVO $vo)
    {
        $data = $vo->toArray();
        $this->fill($data);
        $this->save();
        return $this;
    }
}
