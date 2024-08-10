<?php

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeController = new EmployeeController();
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $date_employment = $_POST['date_employment'];
    $role = $_POST['role'];

    // For security, hash the password
    $hashed_password = md5($password);

    $result = $employeeController->create(array(
        "name" => $name,
        "username" => $username,
        "password" => $hashed_password,
        "date_employment" => $date_employment,
        "role" => $role,
    ));

    if ($result) {
        $message = "New employee added successfully!";
        $messageType = "success";
    } else {
        $message = "Error: Unable to add employee.";
        $messageType = "danger";
    }
}


?>


<div class="container mt-5">
    <h2>Add Employee</h2>
    <?php if ($message != "") : ?>
        <div class="alert alert-<?php echo $messageType; ?>" role="alert">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="date_employment">Date of Employment</label>
            <input type="date" class="form-control" id="date_employment" name="date_employment" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-select" name="role" id="role">
                <option value="admin">admin</option>
                <option value="manager">manager</option>
                <option value="employee">employee</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Add Employee</button>
    </form>
</div>