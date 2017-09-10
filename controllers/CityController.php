<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\filters\Cors;

/**
 * Description of CityController
 *
 * @author Lexa
 */
class CityController extends ActiveController {
    public $modelClass = 'app\models\City';
    
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
    /**
     * Получение уникальных значений 
     * @return type array
     */
    public function actionUnique() {
        $model = new $this->modelClass();
        return $model->getUnique();
    }
    
    public function actionOptions() {
        return "ok";
    }
}
