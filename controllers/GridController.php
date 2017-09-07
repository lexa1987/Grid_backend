<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\filters\Cors;
/**
 * Description of GridController
 *
 * @author Lexa
 */
class GridController extends Controller {
    public $modelClass = 'app\models\Grid';
    
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
    
    public function actionData() {
        $grid = new $this->modelClass();
        return $grid->getData();
    }
    
    public function actionOptions() {
        return "ok";
    }
}
