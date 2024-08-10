<?php
class EmployeeBranch
{
    private static $table = 'employee_branche';

    public static function insert($data)
    {
        return DB::insert(self::$table, $data);
    }

    public static function update($data, $where)
    {
        return DB::update(self::$table, $data, $where);
    }

    public static function delete($where)
    {
        return DB::delete(self::$table, $where);
    }

    public static function getBy($where)
    {
        return DB::getBy(self::$table, $where);
    }

    public static function getAllBy($where)
    {
        return DB::getAllBy(self::$table, $where);
    }

    public static function getAll()
    {
        return DB::getAll(self::$table);
    }
}
