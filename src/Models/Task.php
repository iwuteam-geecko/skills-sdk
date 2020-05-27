<?php


namespace Geecko\Skills\Models;


use Geecko\Skills\VO\LanguageTaskVO;
use Geecko\Skills\VO\TaskVO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Task extends Model
{
    use SoftDeletes;

    protected $table = 'skillservice_tasks';
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = [
        'structure' => 'array',
        'tags' => 'array',
    ];

    public function setStructureAttribute($val)
    {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $this->attributes['structure'] = $val;
    }

    public function setTagsAttribute($val)
    {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $this->attributes['tags'] = $val;
    }

    public function children()
    {
        return $this->hasMany(LanguageTask::class, 'task_id');
    }

    public function saveFromVO(TaskVO $vo)
    {
        $data = $vo->toArray();
        $this->fill(Arr::except($data, ['tasks', 'id']));
        $this->skillservice_id = $vo->id;
        $this->save();
        $tasksIds = [];
        foreach ($vo->tasks as $task) {
            $langVO = new LanguageTaskVO(array_merge($task, ['task_id' => $vo->id, 'type' => $vo->type]));
            $langTask = new LanguageTask();
            $langTask->saveFromVO($langVO);
            $tasksIds[] = $langTask->id;
        }
        $this->children()->whereNotIn('id', $tasksIds)->delete();
        return $this;
    }
}
