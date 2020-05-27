# Skills SDK
Пакет для интеграция с сервисом Skills

## Установка

```bash
composer require iwuteam-geecko/skills-sdk
pip artisan migrate
php artisan vendor:publish --provider=Geecko\Skills\SkillsServiceProvider --tag=config
```

##.env vars
````
SKILLSERVICE_URL="https://skills.9ev.ru/"
SKILLSERVICE_KEY="some key"
SKILLSERVICE_X_COMPANY_ID=id
````

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
 $params = [
    'template_id' => 7, //использовать или template_id или  tasks
    'tasks' => [
            ['type'=>1, 'id'=>98]
        ],
    'without_feedback' => 1, //1 или 0,   
    'autostart' => 1, //1 или 0,   
    'redirect_uri' => 'урл для редиректа',
    'pass_by_tests' => 1, //1 или 0,  
    //возможно будет что-то еще
 ];
```

 либо же
воспользоваться chain методами:
 `setTemplate(), addTask(), skipFeedback(), passByTest(), redirectUrl() и
autostart()`

Последний вызванный метод из `SetTemplate() и addTask()` определяет какая будет создана сессия:
из шаблона или из массива заданий

Так как заданий при создании сессии можно добавить более 1, то допускается множественный вызов `addTask()`

В `addTask()` может быть передан как `LangTask`, так и `['type'=>тип задания, 'id'=>id задания на конкретном языке]`

Для непосредственного запроса и сохранения сессии в конце нужно вызвать `create()`

`skillsSessions()` - нименование полиморфной связи у Sessionable

`getSkillsLastSession()` - получение последней сессии у Sessionable

`newSession()` - создание новой сессии у Sessionable. Можно прокинуть массив `$params` (см. выше)

