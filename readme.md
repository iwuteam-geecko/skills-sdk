# Skills Laravel SDK
Пакет для интеграции с сервисом Skills

## Установка

```pip
composer require iwuteam-geecko/skills-sdk
pip artisan migrate
php artisan vendor:publish --provider=Geecko\Skills\SkillsServiceProvider --tag=config
```

## .env vars
```
SKILLSERVICE_URL="https://skills.9ev.ru/"
SKILLSERVICE_KEY="some key"
SKILLSERVICE_X_COMPANY_ID=id
```

В моделях, где нужно использование Skills необходимо подключить:

```
interface SkillsSessionable

trait SkillableTrait
```

## Использование

Для создания сессий можно использовать метод `newSession()` у `Sessionable` модели
или `SkillService`

в `$params` можно передавать любые параметры для создания ссессии:

```php
use Geecko\Skills\Models\LanguageTask;

$user = User::find(1);
$langTaskModel = LanguageTask::where('language_id', $user->language_id)->first();

$params = [
    'template_id' => 7, //использовать или template_id или tasks
    'tasks' => [
        [
            'id' => $langTaskModel->skillservice_id,
            'type' => $langTaskModel->type,
        ]
    ],
    'without_feedback' => 1, //1 или 0,
    'autostart' => 1, //1 или 0,
    'redirect_uri' => 'урл для редиректа',
    'pass_by_tests' => 1, //1 или 0,
    //возможно будет что-то еще
];

$user->newSession($params)
     ->create();
     
return redirect($session->url);
```

 либо же
воспользоваться chain методами:
 `setTemplate(), addTask(), skipFeedback(), passByTest(), redirect() и
autostart()`

```php
use Geecko\Skills\Models\LanguageTask;

$user = User::find(1);
$langTaskModel = LanguageTask::where('language_id', $user->language_id)->first();
$session = $user->newSession()
                ->addTask($langTaskModel)
                ->autostart()
                ->redirect(route('skills-test-complete'))
                ->skipFeedback()
                ->passByTest()
                ->create();

return redirect($session->url);
```

Последний вызванный метод из `SetTemplate() и addTask()` определяет, какая будет создана сессия:
из шаблона или из массива заданий

Так как заданий при создании сессии можно добавить более 1, то допускается множественный вызов `addTask()`

В `addTask()` может быть передан как `LangTask`, так и `['type'=>тип задания, 'id'=>id задания на конкретном языке]`

Для непосредственного запроса и сохранения сессии в конце нужно вызвать `create()`

`skillsSessions()` - наименование полиморфной связи у Sessionable

`getSkillsLastSession()` - получение последней сессии у Sessionable

`newSession()` - создание новой сессии у Sessionable. Можно прокинуть массив `$params` (см. выше)

`toModelSessionableView()` - необходимо имплементировать данный метод у Sessionable модели для передачи в Skills, пример:
```php
public function toModelSessionableView()
{
    return [
                'external_id' => $this->id,
                'first_name' => $this->name,
                'email' => $this->email,
            ];  
}
```

## Webhook routes

```
skills-service/webhooks/session-started - сессия запущена
skills-service/webhooks/session-finished - сессия завершена
skills-service/webhooks/session-canceled - сессия отменена
skills-service/webhooks/results-received - получены результаты сессии
skills-service/webhooks/feedback-received - получен фидбэк
```

## Events
При вызове вэбхука из Skills соответствующий экшен запускает событие и сохраняет пришедшие данные в сессию
```
SessionStarted::class
SessionFinished::class
SessionCanceled::class
ResultsReceived::class
FeedbackReceived::class
```

## Поля Session
``` 
string 'uid'
string 'url'
integer 'status_id'
string 'redirect_uri'
json 'tasks'
json 'feedback'
json 'template'
json 'score'
integer 'score_percent'
timestamp 'created_at'
timestamp 'started_at'
timestamp 'finished_at'
```
