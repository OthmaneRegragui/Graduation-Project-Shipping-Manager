<?php
class EmployeeTransactionController {
    public function create($data) {
        return EmployeeTransaction::insert($data);
    }

    public function update($data, $id) {
        return EmployeeTransaction::update($data, "id = $id");
    }

    public function delete($id) {
        return EmployeeTransaction::delete("id = $id");
    }

    public function getById($id) {
        return EmployeeTransaction::getBy("id = $id");
    }

    public function getAll() {
        return EmployeeTransaction::getAll();
    }
}
?>
