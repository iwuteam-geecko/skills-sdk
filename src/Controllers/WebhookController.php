<?php


namespace Geecko\Skills\Controllers;


use App\Http\Controllers\Controller;
use Geecko\Skills\Events\FeedbackReceived;
use Geecko\Skills\Events\ResultsReceived;
use Geecko\Skills\Events\SessionCanceled;
use Geecko\Skills\Events\SessionFinished;
use Geecko\Skills\Events\SessionStarted;
use Geecko\Skills\VO\SessionVO;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * Class WebhookController
 * @package Geecko\Skills\Controllers
 * Процесс вызова вебхуков
 * Для каждого события можно указать свой URL, по которому будет осуществлен вызов.
 * Вебхук вызывается методом POST на указанный в настройках URL,
 * передавая тело запроса в формате JSON с информацией по событию. Таймаут запроса - 15 секунд.
 * Если хук вами успешно обработан, то необходимо вернуть в ответ http код 200 OK.
 * Любой другой код, отличный от 200 OK будет интерпретироваться нами как неудачный вызов,
 * после чего будут осуществлены повторные вызовы вебхука согласно таблице:
 * Номер попытки    Повторный вызов
 * 1-4    Через 30 секунд
 * 5-9    Через минуту
 * 10-14    Через 5 минут
 * 15-19    Через 10 минут
 * 20    Прекращение попыток вызова
 */
class WebhookController extends Controller
{

    protected $vo;

    public function __construct(Request $request)
    {
        $data = $request->get('data');
        $this->vo = new SessionVO(Arr::get($data, 'session'));
        if ($feedback = Arr::get($data, 'feedback')) {
            $this->vo->feedback = $feedback;
        }
    }

    /**
     * Сессия начата
     * Вызывается в момент старта сессии кандидатом.
     * @param Request $request
     */
    public function sessionStarted(Request $request)
    {
        event(new SessionStarted($this->vo));
    }

    /**
     * Сессия завершена
     * Вызывается в момент завершения последнего задания сессии кандидатом либо по завершении таймера последнего задания.
     * @param Request $request
     */
    public function sessionFinished(Request $request)
    {
        event(new SessionFinished($this->vo));
    }

    /**
     * Сессия отменена
     * Вызывается в момент отмены сессии, в поле cancelled_reason_id содержится числовой код причины отмены сессии.
     * @param Request $request
     */
    public function sessionCanceled(Request $request)
    {
        event(new SessionCanceled($this->vo));
    }

    /**
     * Получена обратная связь от кандидата
     * Вызывается в момент отправки кандидатом обратной связи.
     * @param Request $request
     */
    public function sessionFeedbackReceived(Request $request)
    {
        event(new FeedbackReceived($this->vo));
    }

    /**
     * Получены результаты автопроверок для сессии
     * Поскольку итоговые автотесты связанные с выполнением кода выполняются асинхронно в очередях,
     * в момент завершения сессии возможно, что тесты по какому-то заданию еще не были запущены.
     * Данный вебхук выхызывается, когда все возможноые автопроверки для сессии выполнены.
     * @param Request $request
     */
    public function sessionResultsReceived(Request $request)
    {
        event(new ResultsReceived($this->vo));
    }
}
