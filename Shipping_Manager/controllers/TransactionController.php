<?php
class TransactionController
{
    public function create($data)
    {
        return Transaction::insert($data);
    }

    public function update($data, $id)
    {
        return Transaction::update($data, "id = $id");
    }

    public function delete($id)
    {
        return Transaction::delete("id = $id");
    }

    public function getById($id)
    {
        return Transaction::getBy("id = $id");
    }

    public function getAll()
    {
        return Transaction::getAll();
    }
    public function getBy($where)
    {
        return Transaction::getBy($where);
    }
}
