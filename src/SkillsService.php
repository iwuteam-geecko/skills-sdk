<?php


namespace Geecko\Skills;

use Geecko\Skills\Interfaces\SkillsSessionable;
use Geecko\Skills\Models\Session;
use Geecko\Skills\Models\Task;
use Geecko\Skills\Models\Template;
use Geecko\Skills\VO\TemplateVO;
use Geecko\Skills\VO\SessionVO;
use Geecko\Skills\VO\TaskVO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;

class SkillsService
{
    protected $client;
    protected $api_url;
    protected $api_key;
    protected $x_company_id;
    const CODING = 'code';
    const TESTS = 'tests';
    const CODE_REVIEW = 'code-review';
    const DATABASE = 'database';
    const TASK_TYPES_CODES = [
        self::CODING => 1,
        self::TESTS => 2,
        self::CODE_REVIEW => 3,
        self::DATABASE => 4,
    ];

    const TASK_TYPES = [
        self::CODE_REVIEW,
        self::CODING,
        self::TESTS,
        self::DATABASE,
    ];

    /**
     * SkillsService constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->api_url = config('skillservice.api_url');
        $this->api_key = config('skillservice.api_key');
        $this->x_company_id = config('skillservice.x_company_id');
        if (!$this->api_url) {
            throw new \Exception('SkillService param missing:SKILLSERVICE_URL in config');
        }
        if (!$this->api_key) {
            throw new \Exception('SkillService param missing:SKILLSERVICE_KEY in config');
        }
        if (!$this->x_company_id) {
            throw new \Exception('SkillService param missing:SKILLSERVICE_X_COMPANY_ID in config');
        }
        $this->client = new Client([
            'verify' => false,
            'base_uri' => $this->api_url,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Company-Id' => $this->x_company_id,
            ]
        ]);

    }

    /**
     * @param $path
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function get($path)
    {
        try {
            return $this->client->request('GET', $path);
        } catch (ClientException $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
            throw $e;
        }


    }

    /**
     * @param $path
     * @param SessionVO $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function post($path, SessionVO $data)
    {
//        dd($data->toArray());
        try {
            return $this->client->request('POST', $path, [
                'form_params' => $data->toArray()
            ]);
        } catch (ClientException $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
            throw $e;
        }

    }

    /**
     * @param $path
     * @param SessionVO $data
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function put($path, SessionVO $data)
    {
        try {
            return $this->client->request('PUT', $path, [
                'form_params' => $data->toArray()
            ]);
        } catch (ClientException $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }
            throw $e;
        }

    }

    /**
     * @param SkillsSessionable $sessionable
     * @param array $params
     * @return SkillsQueryBuilder
     */
    public function newSession(SkillsSessionable $sessionable, array $params = [])
    {
        return new SkillsQueryBuilder($this, $sessionable, $params);
    }

    /**
     * @param string $path
     * @param SessionVO $sessionVO
     * @param SkillsSessionable $sessionable
     * @return Session
     */
    public function createSessionFromVO(string $path, SessionVO $sessionVO, SkillsSessionable $sessionable)
    {
        $request = $this->post($path, $sessionVO);
        $resp = json_decode($request->getBody(), true);
        $session = Arr::get($resp, 'session');
        $sessionVO = new SessionVO($session);
        $model = new Session();
        $model->fill(Arr::except($sessionVO->toArray(), ['candidate']));
        $model->model()->associate($sessionable);
        $model->save();
        return $model;
    }

    /**
     *
     */
    public function getTemplates()
    {
        $ids = [];
        $result = json_decode((string)$this->get('/api/v1/library/templates')->getBody(), true);
        foreach (Arr::get($result, 'templates.results') as $row) {
            $vo = new TemplateVO($row);
            $ids[] = $vo->id;
            if (!Template::where('id', '=', $vo->id)->count()) {
                $model = new Template();
                $model->saveFromVO($vo);
            }
        }
        Template::whereNotIn('id', $ids)->delete();
    }

    /**
     * @throws \Exception
     */
    public function getAllTasks()
    {
        foreach (self::TASK_TYPES as $type) {
            $this->getTasksByType($type);
        }
    }

    /**
     * @param string $type
     * @throws \Exception
     */
    public function getTasksByType(string $type)
    {
        if (!in_array($type, self::TASK_TYPES)) {
            throw new \Exception('No such task type!');
        }
        $ids = [];
        $result = json_decode((string)$this->get('/api/v1/library/tasks/' . $type)->getBody(), true);
        foreach (Arr::get($result, ($type === 'tests' ? 'tests' : 'tasks') . '.results') as $row) {
            $vo = new TaskVO(array_merge($row, ['type' => self::TASK_TYPES_CODES[$type]]));
            $ids[] = $vo->id;
            if (!Task::where('skillservice_id', '=', $vo->id)->count()) {
                $model = new Task();
                $model->saveFromVO($vo);
            }
        }
        Task::where('type', '=', self::TASK_TYPES_CODES[$type])->whereNotIn('skillservice_id', $ids)->delete();

    }
}
