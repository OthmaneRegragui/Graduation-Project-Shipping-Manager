<?php
$employeeController = new EmployeeController();

// Handle POST requests for edit and delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $employeeData = $data['data'] ?? [];

    $response = ['success' => false, 'message' => ''];

    if ($action === 'edit') {
        // Perform edit action
        $success = $employeeController->update($employeeData, $employeeData['id']);
        $response['success'] = $success;
        $response['message'] = $success ? 'Employee updated successfully' : 'Failed to update employee';
    } elseif ($action === 'delete') {
        // Perform delete action
        $success = $employeeController->update(array("active" => 0), $employeeData['id']);
        $response['success'] = $success;
        $response['message'] = $success ? 'Employee deleted successfully' : 'Failed to delete employee';
    }
    echo json_encode($response);
    exit;
}

// Fetch all employees for display
$employees = $employeeController->getAll();
?>

<div class="container mt-5">
    <h2>Employees List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Date of Employment</th>
                <th>Role</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (count($employees) > 0) {
                foreach ($employees as $employee) {
                    echo "<tr>";
                    echo "<td>" . $employee["id"] . "</td>";
                    echo "<td>" . $employee["name"] . "</td>";
                    echo "<td>" . $employee["username"] . "</td>";
                    echo "<td>" . $employee["date_employment"] . "</td>";
                    echo "<td>" . $employee["role"] . "</td>";
                    echo "<td>" . ($employee["active"] == true ? "Active" : "Not active") . "</td>";
                    echo "<td>";
            ?>
                    <p class="d-inline-flex gap-1">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $employee['id']; ?>">Edit employee</button>
                        <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $employee['id']; ?>)">Delete employee</button>
                    </p>
            <?php
                    echo "</td>";
                    echo "</tr>";

                    // Edit Modal
                    echo "<div class='modal fade' id='editModal" . $employee['id'] . "' tabindex='-1' aria-labelledby='editModalLabel" . $employee['id'] . "' aria-hidden='true'>";
                    echo "<div class='modal-dialog'>";
                    echo "<div class='modal-content'>";
                    echo "<div class='modal-header'>";
                    echo "<h5 class='modal-title' id='editModalLabel" . $employee['id'] . "'>Edit Employee</h5>";
                    echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                    echo "</div>";
                    echo "<div class='modal-body'>";
                    echo "<form id='editForm" . $employee['id'] . "'>";
                    echo "<input type='hidden' name='id' value='" . $employee['id'] . "'>";
                    echo "<div class='mb-3'><label for='name' class='form-label'>Name</label><input type='text' class='form-control' name='name' value='" . $employee['name'] . "'></div>";
                    echo "<div class='mb-3'><label for='username' class='form-label'>Username</label><input type='text' class='form-control' name='username' value='" . $employee['username'] . "'></div>";
                    echo "<div class='mb-3'><label for='date_employment' class='form-label'>Date of Employment</label><input type='date' class='form-control' name='date_employment' value='" . $employee['date_employment'] . "'></div>";
                    echo "<div class='mb-3'><label for='role' class='form-label'>Role</label>";
                    echo "<select class='form-select' name='role'>";
                    echo "<option value='admin' " . ($employee['role'] == 'admin' ? 'selected' : '') . ">Admin</option>";
                    echo "<option value='manager' " . ($employee['role'] == 'manager' ? 'selected' : '') . ">Manager</option>";
                    echo "<option value='employee' " . ($employee['role'] == 'employee' ? 'selected' : '') . ">Employee</option>";
                    echo "</select></div>";
                    echo "<div class='mb-3'><label for='active' class='form-label'>Active</label><select class='form-select' name='active'><option value='1' " . ($employee['active'] ? 'selected' : '') . ">Active</option><option value='0' " . (!$employee['active'] ? 'selected' : '') . ">Not active</option></select></div>";
                    echo "</form>";
                    echo "</div>";
                    echo "<div class='modal-footer'>";
                    echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
                    echo "<button type='button' class='btn btn-primary' onclick='submitEditForm(" . $employee['id'] . ")'>Save changes</button>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<tr><td colspan='7'>No employees found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(employeeId) {
        if (confirm("Are you sure you want to delete this employee?")) {
            sendRequest('delete', {
                id: employeeId
            });
        }
    }

    function submitEditForm(employeeId) {
        var form = document.getElementById('editForm' + employeeId);
        var formData = new FormData(form);

        sendRequest('edit', Object.fromEntries(formData));

        // Close the modal after submission
        var modal = bootstrap.Modal.getInstance(document.getElementById('editModal' + employeeId));
        modal.hide();
    }

    function sendRequest(action, data) {
        // Prepare the request data
        var requestData = {
            action: action,
            data: data
        };

        // Send AJAX request
        fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData),
            })
            .then(response => response)
            .then(data => {
                location.reload();
            })
    }
</script>