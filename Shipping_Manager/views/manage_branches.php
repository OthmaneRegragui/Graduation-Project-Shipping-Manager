<?php

$branchController = new BranchController();

// Handle POST requests for edit and delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $branchData = $data['data'] ?? [];

    $response = ['success' => false, 'message' => ''];

    if ($action === 'edit') {
        // Perform edit action
        $success = $branchController->update($branchData, $branchData['id']);
        $response['success'] = $success;
        $response['message'] = $success ? 'Branch updated successfully' : 'Failed to update branch';
    }
    echo json_encode($response);
    exit;
}

// Fetch all branches for display
$branches = $branchController->getAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Branches</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Branches List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Address</th>
                    <th>Space (sqm)</th>
                    <th>Height (m)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($branches) > 0) {
                    foreach ($branches as $branch) {
                        echo "<tr>";
                        echo "<td>" . $branch["id"] . "</td>";
                        echo "<td>" . $branch["name"] . "</td>";
                        echo "<td>" . $branch["city"] . "</td>";
                        echo "<td>" . $branch["address"] . "</td>";
                        echo "<td>" . $branch["space"] . "</td>";
                        echo "<td>" . $branch["height"] . "</td>";
                        echo "<td>";
                ?>
                        <p class="d-inline-flex gap-1">
                            <a type="button" class="btn btn-primary" href="./add_employee_to_branch?branch_id=<?php echo $branch['id']; ?>">Add Employee Branch</a>
                            <a type="button" class="btn btn-info" href="./manage_employee_branch?branch_id=<?php echo $branch['id']; ?>">Manage Employee Branch</a>
                            <?php
                            if (is_admin()) {
                            ?>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $branch['id']; ?>">Edit Branch</button>
                            <?php
                            }


                            ?>
                        </p>
                <?php
                        echo "</td>";
                        echo "</tr>";

                        // Edit Modal
                        echo "<div class='modal fade' id='editModal" . $branch['id'] . "' tabindex='-1' aria-labelledby='editModalLabel" . $branch['id'] . "' aria-hidden='true'>";
                        echo "<div class='modal-dialog'>";
                        echo "<div class='modal-content'>";
                        echo "<div class='modal-header'>";
                        echo "<h5 class='modal-title' id='editModalLabel" . $branch['id'] . "'>Edit Branch</h5>";
                        echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                        echo "</div>";
                        echo "<div class='modal-body'>";
                        echo "<form id='editForm" . $branch['id'] . "'>";
                        echo "<input type='hidden' name='id' value='" . $branch['id'] . "'>";
                        echo "<div class='mb-3'><label for='name' class='form-label'>Name</label><input type='text' class='form-control' name='name' value='" . $branch['name'] . "'></div>";
                        echo "<div class='mb-3'><label for='city' class='form-label'>City</label><input type='text' class='form-control' name='city' value='" . $branch['city'] . "'></div>";
                        echo "<div class='mb-3'><label for='address' class='form-label'>Address</label><input type='text' class='form-control' name='address' value='" . $branch['address'] . "'></div>";
                        echo "<div class='mb-3'><label for='space' class='form-label'>Space (sqm)</label><input type='number' step='0.01' class='form-control' name='space' value='" . $branch['space'] . "'></div>";
                        echo "<div class='mb-3'><label for='height' class='form-label'>Height (m)</label><input type='number' step='0.01' class='form-control' name='height' value='" . $branch['height'] . "'></div>";
                        echo "</form>";
                        echo "</div>";
                        echo "<div class='modal-footer'>";
                        echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
                        echo "<button type='button' class='btn btn-primary' onclick='submitEditForm(" . $branch['id'] . ")'>Save changes</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No branches found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function submitEditForm(branchId) {
            var form = document.getElementById('editForm' + branchId);
            var formData = new FormData(form);

            sendRequest('edit', Object.fromEntries(formData));

            // Close the modal after submission
            var modal = bootstrap.Modal.getInstance(document.getElementById('editModal' + branchId));
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
</body>

</html>