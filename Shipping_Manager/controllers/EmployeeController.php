<?php
class EmployeeController
{
    public function create($data)
    {
        return Employee::insert($data);
    }

    public function update($data, $id)
    {
        return Employee::update($data, "id = $id");
    }

    public function delete($id)
    {
        return Employee::delete("id = $id");
    }

    public function getById($id)
    {
        return Employee::getBy("id = $id");
    }
    public function getBy($where)
    {
        return Employee::getBy($where);
    }
    public function getAll()
    {
        return Employee::getAll();
    }
}
