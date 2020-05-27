<?php


namespace Geecko\Skills\VO;


use Geecko\Skills\Models\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class SessionVO extends AbstractVO
{
    const STATUS_RUNNING = 1; //выполняется
    const STATUS_FINISHED = 2; //завершено
    const STATUS_PAUSED = 3; //на паузе
    const STATUS_CANCELED = 4; //отменено

    public $id;
    public $uid;
    public $status_id;
    public $candidate;
    public $redirect_uri;
    public $tasks = [];
    public $template_id;
    public $feedback;
    public $url;
    public $score;
    public $created_at;
    public $started_at;
    public $finished_at;

    public function __construct($arguments = null)
    {
        $this->id = Arr::get($arguments, 'id');
        $this->uid = Arr::get($arguments, 'uid');
        $this->url = Arr::get($arguments, 'url');
        $this->status_id = Arr::get($arguments, 'status_id');
        $this->candidate = Arr::get($arguments, 'candidate');
        $this->redirect_uri = Arr::get($arguments, 'redirect_uri');
        $this->template_id = Arr::get($arguments, 'template_id');
        $this->created_at = Arr::get($arguments, 'created_ar');
        $this->started_at = Arr::get($arguments, 'started_at');
        $this->finished_at = Arr::get($arguments, 'finished_at');
        if ($tasks = Arr::get($arguments, 'tasks')) {
            $this->tasks = $tasks;
        }
        if ($tasks = Arr::get($arguments, 'tasks_brief')) {
            $this->tasks = $tasks;
        }
        if ($feedback = Arr::get($arguments, 'feedback')) {
            $this->feedback = $feedback;
        }
        if ($score = Arr::get($arguments, 'score')) {
            $this->score = $score;
        }

    }
}
