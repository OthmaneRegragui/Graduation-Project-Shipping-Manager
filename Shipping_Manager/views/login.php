<?php
$employeeController = new EmployeeController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $employee = $employeeController->getBy("username = '$username' AND password = '$password'");

    if ($employee !== false && $employee["active"] == 1) {
        $_SESSION['id'] = $employee["id"];
        $_SESSION['username'] = $employee["username"];
        $_SESSION['name'] = $employee["name"];
        $_SESSION['role'] = $employee["role"];
        header('Location: ' . BASE_URL . "home");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Login</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="form-group mt-2">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>