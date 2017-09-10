<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\Cors;
/**
 * Контроллер для построения таблицы
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
    /**
     * Первоначальные данные для таблицы
     * @return type array
     */
    public function actionData() {
        $grid = new $this->modelClass();
        return $grid->getData();
    }
    /**
     * Обновленные данные для таблицы
     * @return type array
     */
    public function actionUpdate() {
        $grid = new $this->modelClass();
        $grid->setScope(Yii::$app->request->getQueryParam('scope'));
        return $grid->getData();
    }
    
    public function actionOptions() {
        return "ok";
    }
}
