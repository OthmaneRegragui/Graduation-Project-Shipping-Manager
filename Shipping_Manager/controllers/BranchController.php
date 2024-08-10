<?php
class BranchController
{
    public function create($data)
    {
        return Branch::insert($data);
    }

    public function update($data, $id)
    {
        return Branch::update($data, "id = $id");
    }

    public function delete($id)
    {
        return Branch::delete("id = $id");
    }

    public function getById($id)
    {
        return Branch::getBy("id = $id");
    }

    public function getAll()
    {
        return Branch::getAll();
    }
}
