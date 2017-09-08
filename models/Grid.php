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

    private $user;
    private $education;
    private $city;

    function getUser() {
        return $this->user;
    }

    public function parseScope() {
        if ($this->scope == []) {
            $this->user = "(SELECT * FROM `user`)";
        } else {
            $usersArr = array_map(
                function($u){return $u->id;}, 
                json_decode($this->scope)->users);
            $users = implode(', ', $usersArr);
            $this->user = "(SELECT * FROM `user` WHERE `user`.id in ($users) )";
        }
    }

    public function sql() {
        $sql = "SELECT
            u.id,
            u.fullname,
            e.degrees,
            c.cities
        FROM
             $this->user u
        LEFT JOIN
            (SELECT e1.user, concat_ws(', ', e1.degree, e2.degree) degrees 
            FROM (SELECT * FROM `education`) e1 
            LEFT JOIN (SELECT * FROM `education`) e2 
            ON e1.user = e2.user AND e1.id != e2.id 
            GROUP BY e1.user) e
        ON
            u.id = e.user  
        LEFT JOIN
            (SELECT c1.user, concat_ws(', ', c1.name, c2.name) cities  
            FROM (SELECT * FROM `city`) c1 
            LEFT JOIN (SELECT * FROM `city`) c2 
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
