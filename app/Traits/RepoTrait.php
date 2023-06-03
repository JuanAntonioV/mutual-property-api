<?php

namespace App\Traits;

trait RepoTrait
{

  abstract static function getDbTable();

  public static function getAll(array $columns = array('*'))
  {
    return self::getDbTable()->select(...$columns)->get();
  }

  public static function findById($primaryId, array $columns = array('*'), string $primaryFieldName = 'id')
  {
    return self::getDbTable()->select(...$columns)->where($primaryFieldName, $primaryId)->first();
  }

  public static function arrayWhere(array $conditions = array(), array $columns = array('*'))
  {
    $query = self::getDbTable()->select(...$columns);

    if (gettype($conditions[0]) === 'array') {
      foreach ($conditions as $condition) {
        $query->where(...$condition);
      }
    } else {
      $query->where(...$conditions);
    }

    return $query;
  }

  public static function update($whereValue,string $whereField = "id", array $data = array())
  {
    return self::getDbTable()->where($whereField, $whereValue)->update($data);
  }

  public static function insert(array $data = array())
  {
    return self::getDbTable()->insert($data);
  }
}
