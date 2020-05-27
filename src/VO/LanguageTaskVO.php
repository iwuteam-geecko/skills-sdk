<?php


namespace Geecko\Skills\VO;


use Geecko\Skills\Models\LanguageTask;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class LanguageTaskVO extends AbstractVO
{
    public $id;
    public $name;
    public $instructions;
    public $code;
    public $time_limit;
    public $has_tests;
    public $is_demo;
    public $is_tests_hidden;
    public $structure = [];
    public $created_at;
    public $language;
    public $tests;
    public $task_id;
    public $type;

    public function __construct($arguments = null)
    {
        $this->id = Arr::get($arguments, 'id');
        $this->name = Arr::get($arguments, 'name');
        $this->instructions = Arr::get($arguments, 'instructions');
        $this->code = Arr::get($arguments, 'code');
        $this->tests = Arr::get($arguments, 'tests');
        $this->time_limit = Arr::get($arguments, 'time_limit');
        $this->has_tests = Arr::get($arguments, 'has_tests');
        $this->is_demo = Arr::get($arguments, 'is_demo');
        $this->is_tests_hidden = Arr::get($arguments, 'is_tests_hidden');
        $this->structure = Arr::get($arguments, 'structure');
        $this->created_at = Arr::get($arguments, 'created_at');
        $this->language = Arr::get($arguments, 'language');
        $this->task_id = Arr::get($arguments, 'task_id');
        $this->type = Arr::get($arguments, 'type');
    }
}
