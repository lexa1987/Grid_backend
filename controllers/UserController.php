<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\Cors;

/**
 * Поле Пользователи
 *
 * @author Lexa
 */
class UserController extends ActiveController {
    
    public $modelClass = 'app\models\User';
    
    public function behaviors(){
        $behaviors = parent::behaviors();
        
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page', 'X-Pagination-Page-Count'],
                ],
        ];        
       
        return $behaviors;
        
    }
}
