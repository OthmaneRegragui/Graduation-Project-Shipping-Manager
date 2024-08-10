<?php

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branchController = new BranchController();
    $name = $_POST['name'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $space = $_POST['space'];
    $height = $_POST['height'];

    $result = $branchController->create(array(
        "name" => $name,
        "city" => $city,
        "address" => $address,
        "space" => $space,
        "height" => $height,
    ));

    if ($result) {
        $message = "New branch added successfully!";
        $messageType = "success";
    } else {
        $message = "Error: Unable to add branch.";
        $messageType = "danger";
    }
}
?>

<div class="container mt-5">
    <h2>Add Branch</h2>
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
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="form-group">
            <label for="space">Space (sqm)</label>
            <input type="number" step="0.01" class="form-control" id="space" name="space" required>
        </div>
        <div class="form-group">
            <label for="height">Height (m)</label>
            <input type="number" step="0.01" class="form-control" id="height" name="height" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Add Branch</button>
    </form>
</div>