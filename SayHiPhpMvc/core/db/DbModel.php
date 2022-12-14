<?php

namespace app\core\db;

use PDO;
use app\core\Model;
use app\core\Application;

abstract class DbModel extends Model
{

    abstract public function tableName(): string;
    abstract public function attributes(): array;
    abstract public function primaryKey():string;
    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn ($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ")
        VALUES(" . implode(',', $params) . ")");

        foreach($attributes as $attribute)
        {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }
        $statement->execute();
        return true;
    }
    public function select()
    {
        $tableName = $this->tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS);
        return $result;
    }
    public static function find($where, $tableName) { // [email=>nisanurrunasin@gmail.com, firstName=> Nisanur]
        $tableName = $tableName;
        $attributes = array_keys($where);
        $sql = implode("AND ",array_map(fn($attr)=>"$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key=>$value){
            $statement->bindValue(":$key",$value);
        }
        $statement->execute();
        return $statement->fetchObject(static::class); // gives me instance
    }
    public function findOne($where) { // [email=>nisanurrunasin@gmail.com, firstName=> Nisanur]
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ",array_map(fn($attr)=>"$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach($where as $key=>$value){
            $statement->bindValue(":$key",$value);
        }
        $statement->execute();
        return $statement->fetchObject(static::class); // gives me instance
    }
    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}
