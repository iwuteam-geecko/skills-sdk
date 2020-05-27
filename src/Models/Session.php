<?php


namespace Geecko\Skills\Models;


use Carbon\Carbon;
use Geecko\Skills\VO\SessionVO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Session extends Model
{
    protected $table = 'skillservice_sessions';
    protected $guarded = ['template_id'];
    public $timestamps = false;
    protected $casts = [
        'tasks' => 'array',
        'feedback' => 'array',
        'template' => 'template',
        'score' => 'array',
        'created_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function setTasksAttribute($val)
    {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $this->attributes['tasks'] = $val;
    }

    public function setFeedbackAttribute($val)
    {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $this->attributes['feedback'] = $val;
    }

    public function setScoreAttribute($val)
    {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $this->attributes['score'] = $val;
    }

    public function setCreatedAtAttribute($val)
    {
        $this->attributes['created_at'] = $this->parseDate($val);
    }

    public function setStartedAtAttribute($val)
    {
        $this->attributes['started_at'] = $this->parseDate($val);
    }

    public function setFinishedAtAttribute($val)
    {
        $this->attributes['finished_at'] = $this->parseDate($val);
    }

    public function saveFromVO(SessionVO $vo)
    {
        $data = $vo->toArray();
        $this->fill(Arr::except($data, ['candidate']));
        if ($vo->score && $vo->score['score'] && $vo->score['max']) {
            $this->score_percent = round($vo->score['score'] * 100 / $vo->score['max']);
        }
        $this->save();
        return $this;
    }

    protected function parseDate($d)
    {
        if ($d instanceof \MongoDB\BSON\UTCDateTime) {

            try {
                $date = Carbon::createFromTimestampMs($d->__toString())->format('Y-m-d H:i:sO');
                return $date;
            } catch (\Exception $e1) {
                return null;
            }
        }
        if (gettype($d) === 'integer') {

            try {
                $date = Carbon::createFromTimestampUTC($d->__toString())->format('Y-m-d H:i:sO');
                return $date;
            } catch (\Exception $e) {
                return null;
            }
        }
        try {

            $date = Carbon::parse($d)->format('Y-m-d H:i:sO');
            return $date;
        } catch (\Exception $e) {
            return null;
        }

    }
}
