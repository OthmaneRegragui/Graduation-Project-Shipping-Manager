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

$result_employee_in_branch = $employeeBranchController->getAllBy("branch_id = $branch_id");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    if (isset($id)) {
        try {
            $result_employee_in_branch_tmp = $employeeBranchController->getById($id);
            $currentStatus = $result_employee_in_branch_tmp["active"];
            $newStatus = $currentStatus == 1 ? 0 : 1;

            $employeeBranchController->update(array("active" => $newStatus), $id);

            echo json_encode(['status' => 'success', 'new_status' => $newStatus]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
?>

<div class="container mt-5">
    <h2>Manage Employee Branch : <?= htmlspecialchars($branch["name"], ENT_QUOTES, 'UTF-8') ?></h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employee Id</th>
                <th>Employee Name</th>
                <th>Employee Username</th>
                <th>Status On Branch</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($result_employee_in_branch as $result) {
                $employee = $employeeController->getById($result['employee_id']);
                if (!$employee["active"]) {
                    continue;
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($result['employee_id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($employee["name"], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($employee["username"], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $result["active"] ? "Active" : "Not Active" ?></td>
                    <td>
                        <button class="btn btn-warning" onclick="toggleEmployeeStatus(<?= htmlspecialchars($result['id'], ENT_QUOTES, 'UTF-8') ?>)">
                            <?= $result["active"] ? "Deactivate" : "Activate" ?>
                        </button>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<script>
    function toggleEmployeeStatus(id) {
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: {
                id: id,
            },
            success: function(response) {
                location.reload();
            },
        });
    }
</script>