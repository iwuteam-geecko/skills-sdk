<?php


namespace Geecko\Skills\VO;


use Geecko\Skills\Models\Template;
use Illuminate\Support\Arr;

class TemplateVO extends AbstractVO
{
    public $id;
    public $name;
    public $welcome_text;
    public $version;

    public function __construct($arguments = null)
    {
        $this->id = Arr::get($arguments, 'id');
        $this->name = Arr::get($arguments, 'name');
        $this->welcome_text = Arr::get($arguments, 'welcome_text');
        $this->version = Arr::get($arguments, 'version');
    }
}
