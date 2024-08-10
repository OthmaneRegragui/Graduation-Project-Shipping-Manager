<?php
class StockController
{
    public function create($data)
    {
        return Stock::insert($data);
    }

    public function update($data, $id)
    {
        return Stock::update($data, "id = $id");
    }

    public function delete($id)
    {
        return Stock::delete("id = $id");
    }

    public function getById($id)
    {
        return Stock::getBy("id = $id");
    }
    public function getBy($where)
    {
        return Stock::getBy($where);
    }


    public function getAll()
    {
        return Stock::getAll();
    }
    public function getAllBy($where)
    {
        return Stock::getAllBy($where);
    }
}
