<?php

namespace app\controllers;

use yii\rest\ActiveController;

/**
 * Поле Пользователи
 *
 * @author Lexa
 */
class UserController extends ActiveController {
    
    public $modelClass = 'app\models\User';
}
