<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * Модель для генерации таблицы
 *
 * @author Lexa
 */
class Grid extends Model {
    /**
     *Область выбираемых значений
     * @var type array
     */
    private $scope = [];
    /**
     *Параметры для sql запросов
     * @var type array
     */
    private $params = [];
            
    function setScope($scope) {
        $this->scope = $scope;
    }

    private $user = "(SELECT * FROM `user`)";
    private $education = "(SELECT * FROM `education`)";
    private $city = "(SELECT * FROM `city`)";

    function getUser() {
        return $this->user;
    }
    /**
     * Парсинг для полей таблицы грида
     * @param type $scope область
     * @param type $column поле грида (таблица в БД)
     * @param type $field параметр парсинга
     */
    private function parseColumnScope($scope, $column, $field) {
        $arr = array_map(
                function($elem) use (&$field){return $elem->$field;},
                $scope);
        $this->params[":$column"] = implode(',', $arr);
        $this->$column = "(SELECT * FROM `$column` WHERE FIND_IN_SET($field, :$column) )";
    }
    /**
     * Метод для парсинга значений и подготовка sql-запросов
     */
    private function parseScope() {
        if ($this->scope != []) {
            $scope = json_decode($this->scope);
            if(property_exists($scope, 'users') && $scope->users!=[]) { $this->parseColumnScope($scope->users, 'user', 'id');}
            if(property_exists($scope, 'education')&& $scope->education!=[]) { $this->parseColumnScope($scope->education, 'education', 'degree');}
            if(property_exists($scope, 'city')&& $scope->city!=[]) { $this->parseColumnScope($scope->city, 'city', 'name');}
        }
    }
    /**
     * Подготовка sql-запрроса
     * @return type String
     */
    public function sql() {
        $this->parseScope();
        $sql = "SELECT
            u.id,
            u.fullname,
            e.degrees,
            c.cities
        FROM
             $this->user u
        INNER JOIN
            (SELECT e1.user, concat_ws(', ', e1.degree, e2.degree) degrees 
            FROM $this->education e1 
            LEFT JOIN $this->education e2 
            ON e1.user = e2.user AND e1.id != e2.id 
            GROUP BY e1.user) e
        ON
            u.id = e.user  
        INNER JOIN
            (SELECT c1.user, concat_ws(', ', c1.name, c2.name) cities  
            FROM $this->city c1 
            LEFT JOIN $this->city c2 
            ON c1.user = c2.user AND c1.id != c2.id
            GROUP BY c1.user) c
        ON
            u.id = c.user";
        return $sql;
    }
    /**
     * Вывод данных для таблицы
     * @return type array
     */        
    public function getData() {
        $sql =$this->sql();
        $params = $this->params;
        if ($params == []) {
            $all = Yii::$app->db->createCommand($sql)->queryAll();
        } else {
            $all = Yii::$app->db->createCommand($sql, $params)->queryAll();
        }
        return $all;
    }
    
}
