<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * Description of Grid
 *
 * @author Lexa
 */
class Grid extends Model {
    
    private $scope = [];
    
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
    
    private function parseColumnScope($scope, $column, $field) {
        $arr = array_map(
                function($elem) use (&$field){return $elem->$field;},
                $scope);
        $this->params[":$column"] = implode(',', $arr);
        $this->$column = "(SELECT * FROM `$column` WHERE FIND_IN_SET($field, :$column) )";
    }

    private function parseScope() {
        if ($this->scope != []) {
            $scope = json_decode($this->scope);
            if(property_exists($scope, 'users') && $scope->users!=[]) { $this->parseColumnScope($scope->users, 'user', 'id');}
            if(property_exists($scope, 'education')&& $scope->education!=[]) { $this->parseColumnScope($scope->education, 'education', 'degree');}
            if(property_exists($scope, 'city')&& $scope->city!=[]) { $this->parseColumnScope($scope->city, 'city', 'name');}
        }
    }

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
