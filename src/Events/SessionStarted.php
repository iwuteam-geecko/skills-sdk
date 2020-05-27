<?php


namespace Geecko\Skills\Events;


use Geecko\Skills\Models\Session;
use Geecko\Skills\VO\SessionVO;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionStarted
{
    use Dispatchable, SerializesModels;

    public $session;

    public function __construct(SessionVO $sessionVO)
    {
        $this->session = $sessionVO;
        $model = Session::where('uid', '=', $sessionVO->uid)->first();
        if ($model) {
            $model->saveFromVO($sessionVO);
        }
    }
}
