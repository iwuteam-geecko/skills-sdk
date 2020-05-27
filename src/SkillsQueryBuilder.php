<?php


namespace Geecko\Skills;


use Geecko\Skills\Interfaces\SkillsSessionable;
use Geecko\Skills\Models\LanguageTask;
use Geecko\Skills\Models\Task;
use Geecko\Skills\Models\Template;
use Geecko\Skills\VO\SessionVO;

class SkillsQueryBuilder
{

    /**
     * @var SkillsService
     */
    protected $service;
    /**
     * @var SkillsSessionable
     */
    protected $sessionable;
    /**
     * @var array
     */
    protected $params = [
        'template_id' => null,
        'tasks' => [],
        'candidate' => []
    ];
    /**
     * @var string
     */
    protected $path;

    /**
     * SkillsQueryBuilder constructor.
     * @param SkillsService $service
     * @param SkillsSessionable $sessionable
     * @param array $params
     */
    public function __construct(SkillsService $service, SkillsSessionable $sessionable, array $params = [])
    {
        $this->service = $service;
        $this->sessionable = $sessionable;
        if (count($params)) {
            $this->params = $params;
        }
    }

    /**
     * @param string $url
     * @return $this
     */
    public function redirect(string $url)
    {
        $this->params['redirect_uri'] = $url;
        return $this;
    }

    /**
     * @param bool $val
     * @return $this
     */
    public function skipFeedback(bool $val)
    {
        $this->params['without_feedback'] = $val ? 1 : 0;
        return $this;
    }

    /**
     * @param bool $val
     * @return $this
     */
    public function autostart(bool $val)
    {
        $this->params['autostart'] = $val ? 1 : 0;
        return $this;
    }

    /**
     * @param bool $val
     * @return $this
     */
    public function passByTests(bool $val)
    {
        $this->params['pass_by_tests'] = $val ? 1 : 0;
        return $this;
    }

    /**
     * Темлпейт
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        if ($template instanceof Template) {
            $this->params['template_id'] = $template->id;
        } else {
            $this->params['template_id'] = $template;
        }
        $this->path = '/api/v1/sessions/from-template';
        return $this;
    }

    /**
     * Тасков можно добавить сколько угодно!
     * @param array|LanguageTask $taskObject
     * @return $this
     */
    public function addTask($taskObject)
    {
        if ($taskObject instanceof LanguageTask) {
            $this->params['tasks'][] = [
                'id' => $taskObject->skillservice_id,
                'type' => $taskObject->type,
            ];
        } else {
            $this->params['tasks'][] = $taskObject;
        }
        $this->path = '/api/v1/sessions/from-tasks';
        return $this;
    }

    /**
     * @return Models\Session
     * @throws \Throwable
     */
    public function create()
    {
        if (!$this->path) {
            throw new \Exception('No template or tasks provided to skills request');
        }
        if (strpos($this->path, 'template')) {
            unset($this->params['tasks']);
            unset($this->params['autostart']);
            unset($this->params['without_feedback']);
            unset($this->params['pass_by_tests']);
        } else {
            unset($this->params['template_id']);
        }
        $sessionVO = new SessionVO($this->params);
        $sessionVO->candidate = $this->sessionable->toModelSessionableView();
        try {
            return $this->service->createSessionFromVO($this->path, $sessionVO, $this->sessionable);
        } catch (\Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
            throw $e;
        }
    }
}
