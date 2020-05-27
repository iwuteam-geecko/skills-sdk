<?php


namespace Geecko\Skills\Traits;

use Geecko\Skills\Models\Session;
use Geecko\Skills\Models\Task;
use Geecko\Skills\Models\Template;
use Geecko\Skills\SkillsService;
use Geecko\Skills\VO\SessionVO;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Trait SkillableTrait
 * @package Geecko\Skills\Traits
 */
trait SkillableTrait
{
    /**
     * Set the polymorphic relation.
     *
     * @return MorphMany
     */
    public function skillsSessions()
    {
        return $this->morphMany(Session::class, 'model');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|MorphMany|object|null
     */
    public function getSkillsLastSession()
    {
        return $this->skillsSessions()->latest('id')->first();
    }

    /**
     * @param array $params параметры для запроса skills
     * @return \Geecko\Skills\SkillsQueryBuilder
     */
    public function newSession(array $params = [])
    {
        $skillsService = new SkillsService();
        return $skillsService->newSession($this, $params);
    }
}
