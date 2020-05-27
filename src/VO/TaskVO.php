<?php


namespace Geecko\Skills\VO;


use Geecko\Skills\Models\Task;
use Illuminate\Support\Arr;

class TaskVO extends AbstractVO
{
    public $id;
    public $name;
    public $type;
    public $tasks = [];
    public $tags = [];
    public $task_instructions;
    public $internal_notes;
    public $time_limit;
    public $difficulty;
    public $is_demo;
    public $structure = [];
    public $created_at;
    public $updated_at;

    public function __construct($arguments = null)
    {
        $this->id = Arr::get($arguments, 'id');
        $this->name = Arr::get($arguments, 'name');
        $this->type = Arr::get($arguments, 'type');
        $this->tasks = Arr::get($arguments, 'tasks', []);
        $this->tags = Arr::get($arguments, 'tags', []);
        $this->task_instructions = Arr::get($arguments, 'task_instructions');
        $this->internal_notes = Arr::get($arguments, 'internal_notes');
        $this->time_limit = Arr::get($arguments, 'time_limit');
        $this->difficulty = Arr::get($arguments, 'difficulty');
        $this->is_demo = Arr::get($arguments, 'is_demo');
        $this->structure = Arr::get($arguments, 'structure', []);
        $this->created_at = Arr::get($arguments, 'created_at');
        $this->updated_at = Arr::get($arguments, 'updated_at');
    }
}
