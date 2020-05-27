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

Использование

Для создания сессий можно использовать метод `newSession()->create()` у `Sessionable` модели
или `SkillService`

в `$params` можно передавать любые параметры для создания ссессии, либо же
воспользоваться chain методами:
 `setTemplate(), addTask(), skipFeedback(), passByTest(), redirectUrl() и
autostart()`

Последний вызванный метод из `SetTemplate() и addTask()` определяет какая будет создана сессия:
из шаблона или из массива заданий

Так как заданий при создании сессии можно добавить более 1, то допускается множественный вызов `addTask()`

в addTask() может быть передан как `LangTask`, так и `['type'=>тип задания, 'id'=>id задания на конкретном языке]`


