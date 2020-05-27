<?php


namespace Geecko\Skills\Models;


use Geecko\Skills\VO\LanguageTaskVO;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class LanguageTask extends Model
{
    use SoftDeletes;

    protected $table = 'skillservice_language_tasks';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'tests' => 'array',
        'structure' => 'array',
    ];

    public function setTestsAttribute($val)
    {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $this->attributes['tests'] = $val;
    }

    public function setStructureAttribute($val)
    {
        if (is_array($val)) {
            $val = json_encode($val);
        }
        $this->attributes['structure'] = $val;
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function saveFromVO(LanguageTaskVO $vo)
    {
        $data = $vo->toArray();
        $this->fill(Arr::except($data, ['id', 'language']));
        $this->skillservice_id = $vo->id;
        $this->language_id = $vo->language['id'];
        $this->language_name = $vo->language['name'];
        $this->language_code = $vo->language['code'];
        $this->save();
        return $this;
    }
}
