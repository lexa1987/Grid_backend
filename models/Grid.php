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
    
    function setScope($scope) {
        $this->scope = $scope;
    }

    private $user = "(SELECT * FROM `user`)";
    private $education = "(SELECT * FROM `education`)";
    private $city = "(SELECT * FROM `city`)";

    function getUser() {
        return $this->user;
    }
    
    private function parseUserScope($userScope) {
        $usersArr = array_map(
                function($u){return $u->id;}, 
                $userScope);
        $users = implode(', ', $usersArr);
        $this->user = "(SELECT * FROM `user` WHERE `user`.id in ($users) )";
    }
    
    private function parseEducationScope($educationScope) {
        $educationArr = array_map(
                function($e){return "'".$e->degree."'";}, 
                $educationScope);
        $education = implode(', ', $educationArr);
        $this->education = "(SELECT * FROM `education` WHERE `education`.degree in ($education) )";
    }
    
    private function parseCityScope($cityScope) {
        $citynArr = array_map(
                function($c){return "'".$c->name."'";}, 
                $cityScope);
        $city = implode(', ', $citynArr);
        $this->city = "(SELECT * FROM `city` WHERE `city`.name in ($city) )";
    }

    private function parseScope() {
        if ($this->scope != []) {
            $scope = json_decode($this->scope);
            if(property_exists($scope, 'users') && $scope->users!=[]) { $this->parseUserScope($scope->users);}
            if(property_exists($scope, 'education')&& $scope->education!=[]) { $this->parseEducationScope($scope->education);}
            if(property_exists($scope, 'city')&& $scope->city!=[]) { $this->parseCityScope($scope->city);}
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
        $all = Yii::$app->db->createCommand($this->sql())->queryAll();
        return $all;
    }
    
}
