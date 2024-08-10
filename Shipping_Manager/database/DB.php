<?php
class DB
{
    static public function connect()
    {
        $db = new PDO("mysql:host=" . host . ";dbname=" . dbname, dbuser, dbpassword);
        $db->exec('set names utf8');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        return $db;
    }

    static public function insert($table, $data)
    {
        $db = self::connect();
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $db->prepare($sql);
        return $stmt->execute($data);
    }

    static public function update($table, $data, $where)
    {
        $db = self::connect();
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "$key = :$key, ";
        }
        $fields = rtrim($fields, ", ");
        $sql = "UPDATE $table SET $fields WHERE $where";
        $stmt = $db->prepare($sql);
        return $stmt->execute($data);
    }

    static public function delete($table, $where)
    {
        $db = self::connect();
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }

    static public function getBy($table, $where)
    {
        $db = self::connect();
        $sql = "SELECT * FROM $table WHERE $where";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function getAll($table)
    {
        $db = self::connect();
        $sql = "SELECT * FROM $table";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function getAllBy($table, $where)
    {
        $db = self::connect();
        $sql = "SELECT * FROM $table WHERE $where";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function checkIfExist($array)
    {
        if ($array !== false) {
            return count($array);
        }
        return 0;
    }
}
