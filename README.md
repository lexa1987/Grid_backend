Проект создан на Yii 2 Basic Project Template (http://www.yiiframework.com/)

Данный проект представляет сервис REST API, который имеет следующие маршруты:

“GET http://hostname/web/grid “ – получение данных для таблицы (грида).
“GET http://hostname/web/grid/update “ – получение данных для таблицы (грида), требуется указать параметр scope (JSON-объект содержащий перечень выводимых полей)
За работу данных маршрутов отвечает:
-  модель  app\models\Grid
-  контроллер app\controllers\GridController

“GET http://hostname/web/users“ – получение списка всех пользователей
За работу данного маршрута отвечает:
-  модель  app\models\User
-  контроллер app\controllers\UserController

“GET http://hostname/web/education/unique“ – получение списка видов образования
За работу данного маршрута отвечает:
-  модель  app\models\Education
-  контроллер app\controllers\EducationController

“GET http://hostname/web/city/unique“ – получение списка городов
За работу данного маршрута отвечает:
-  модель  app\models\City
-  контроллер app\controllers\CityController

Все маршруты конфигурируются в файле config/web.php
Конфигурация БД указывается в файле: config/db.php
