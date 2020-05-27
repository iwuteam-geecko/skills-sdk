<?php


namespace Geecko\Skills\Interfaces;


use Geecko\Skills\Models\Task;
use Geecko\Skills\Models\Template;
use Geecko\Skills\VO\SessionVO;

/**
 * Interface SkillsSessionable
 * @package Geecko\Skills\Interfaces
 */
interface SkillsSessionable
{

    /**
     * @return mixed
     */
    public function skillsSessions();

    /**
     * @return mixed
     */
    public function getSkillsLastSession();

    /**
     * @param array $params параметры для запроса skills
     * @return \Geecko\Skills\SkillsQueryBuilder
     */
    public function newSession(array $params = []);
    /**
     *  {
     * "external_id": $this->id,
     * "first_name": "Артем",
     * "last_name": "Прошин",
     * "email": $this->email,
     * "class": self::class
     *  }
     * @return mixed
     */
    public function toModelSessionableView();

}
