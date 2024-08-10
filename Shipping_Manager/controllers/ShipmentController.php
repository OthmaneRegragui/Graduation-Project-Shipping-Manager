<?php
class ShipmentController
{
    public function create($data)
    {
        return Shipment::insert($data);
    }

    public function update($data, $id)
    {
        return Shipment::update($data, "id = $id");
    }

    public function delete($id)
    {
        return Shipment::delete("id = $id");
    }

    public function getById($id)
    {
        return Shipment::getBy("id = $id");
    }

    public function getAll()
    {
        return Shipment::getAll();
    }
    public function getAllBy($where)
    {
        return Shipment::getAllBy($where);
    }
}
