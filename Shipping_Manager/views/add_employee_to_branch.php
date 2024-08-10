<?php
$branchController = new BranchController();
$employeeController = new EmployeeController();
$employeeBranchController = new EmployeeBranchController();
$branch_id = $_GET['branch_id'];
if (!isset($branch_id)) {
    Redirect::to("./home");
}

try {
    $branch = $branchController->getById($branch_id);
    if (!$branch) {
        Redirect::to("./home");
    }
} catch (Exception $e) {
    Redirect::to("./home");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    if (isset($employee_id)) {
        try {
            $employee = $employeeController->getById($employee_id);
            if (!$employee || !$employee["active"]) {
                echo "<div class='alert alert-danger mt-3'>Employee not found.</div>";
            } else {
                $search_result = $employeeBranchController->search($employee_id, $branch_id);
                $count_result = DB::checkIfExist($search_result);
                if ($count_result == 0) {
                    $employeeBranchController->create(array(
                        "branch_id" => $branch_id,
                        "employee_id" => $employee_id,
                    ));
                    echo "<div class='alert alert-success mt-3'>Employee added to branch successfully.</div>";
                } else {
                    $employeeBranchController->update(array("active" => true), $search_result["id"]);
                    echo "<div class='alert alert-primary mt-3'>Employee active again to branch successfully.</div>";
                }
            }
        } catch (Exception $e) {
            echo "<div class='alert alert-danger mt-3'>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger mt-3'>Employee ID is required.</div>";
    }
}

?>

<div class="container mt-5">
    <h2>Add Employee To Branch</h2>
    <form method="post">
        <div class="input-group mb-3">
            <span class="input-group-text">Branch Name</span>
            <input type="text" class="form-control" value="<?= htmlspecialchars($branch["name"], ENT_QUOTES, 'UTF-8') ?>" disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">Branch City</span>
            <input type="text" class="form-control" value="<?= htmlspecialchars($branch["city"], ENT_QUOTES, 'UTF-8') ?>" disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">Branch Address</span>
            <input type="text" class="form-control" value="<?= htmlspecialchars($branch["address"], ENT_QUOTES, 'UTF-8') ?>" disabled>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text">Employee Id</span>
            <input type="number" class="form-control" name="employee_id" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Add Employee To Branch</button>
    </form>
</div>