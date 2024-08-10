<?php
class EmployeeBranchController
{
    public function create($data)
    {
        return EmployeeBranch::insert($data);
    }

    public function update($data, $id)
    {
        return EmployeeBranch::update($data, "id = $id");
    }

    public function delete($id)
    {
        return EmployeeBranch::delete("id = $id");
    }

    public function getById($id)
    {
        return EmployeeBranch::getBy("id = $id");
    }
    public function search($employee_id, $branch_id)
    {
        $result = EmployeeBranch::getBy("employee_id = '$employee_id' and branch_id = '$branch_id'");
        return $result;
    }

    public function getAll()
    {
        return EmployeeBranch::getAll();
    }
    public function getAllBy($where)
    {
        return EmployeeBranch::getAllBy($where);
    }
}
