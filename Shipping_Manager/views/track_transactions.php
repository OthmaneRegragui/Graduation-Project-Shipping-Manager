<?php
$branchController = new BranchController();
$productController = new ProductController();
$stockController = new StockController();
$transactionController = new TransactionController();
$shipmentController = new ShipmentController();

$registration_number = isset($_GET["registration_number"]) ? $_GET["registration_number"] : "";

if (!empty($registration_number)) {
    $transaction = $transactionController->getBy("registration_number = '$registration_number'");
    $from_branch = $branchController->getById($transaction["from_branch_id"]);
    $to_branch = $branchController->getById($transaction["to_branch_id"]);
    $products_shipment = $shipmentController->getAllBy("registration_number = '$registration_number'");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_number = isset($_POST['registration_number']) ? $_POST['registration_number'] : null;

    if (!empty($registration_number)) {
        $transaction = $transactionController->getBy("registration_number = '$registration_number'");
        $arrive_confirm = $transaction['arrive_confirm'] == 1 ? 0 : 1; // Toggle the status

        if ($transactionController->update(array("arrive_confirm" => $arrive_confirm), $transaction["id"])) {
            // Add or remove products from stock based on arrival status
            foreach ($products_shipment as $product) {
                $product_id = $product["product_id"];
                $quantity = $product["quantity"];
                $to_branch_id = $transaction["to_branch_id"];

                if ($arrive_confirm == 1) {
                    // Add to stock
                    $stock = $stockController->getBy("product_id = '$product_id' AND branch_id = '$to_branch_id'");
                    if ($stock) {
                        $new_quantity = $stock['quantity'] + $quantity;
                        $stockController->update(array("quantity" => $new_quantity), $stock["id"]);
                    } else {
                        $stockController->create(array("product_id" => $product_id, "branch_id" => $to_branch_id, "quantity" => $quantity));
                    }
                } else {
                    // Remove from stock
                    $stock = $stockController->getBy("product_id = '$product_id' AND branch_id = '$to_branch_id'");
                    if ($stock) {
                        $new_quantity = $stock['quantity'] - $quantity;
                        if ($new_quantity > 0) {
                            $stockController->update(array("quantity" => $new_quantity), $stock["id"]);
                        } else {
                            $stockController->delete($stock["id"]);
                        }
                    }
                }
            }

            echo "<div class='alert alert-success'>Status updated successfully.</div>";
            $transaction = $transactionController->getBy("registration_number = '$registration_number'"); // Refresh transaction data
        } else {
            echo "<div class='alert alert-danger'>Failed to update status.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Please provide a registration number and status.</div>";
    }
}
?>

<div class="container mt-5">
    <h2>Track Transaction</h2>
    <form method="get">
        <div class="form-group">
            <label for="registration_number">Transaction Registration Number</label>
            <input type="text" class="form-control" id="registration_number" name="registration_number" value="<?= htmlspecialchars($registration_number) ?>">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Track</button>
    </form>

    <?php if (!empty($registration_number) && $transaction) { ?>
        <h3 class="mt-5">Transaction Details</h3>
        <table class="table table-bordered text-center">
            <tbody>
                <tr>
                    <th>Registration Number</th>
                    <td><?= htmlspecialchars($transaction['registration_number']) ?></td>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <th></th>
                    <td>Branch Id</td>
                    <td>Branch Name</td>
                    <td>Branch City</td>
                    <td>Branch Address</td>
                </tr>
                <tr>
                    <th>From Branch</th>
                    <td><?= htmlspecialchars($transaction['from_branch_id']) ?></td>
                    <td><?= htmlspecialchars($from_branch['name']) ?></td>
                    <td><?= htmlspecialchars($from_branch['city']) ?></td>
                    <td><?= htmlspecialchars($from_branch['address']) ?></td>
                </tr>
                <tr>
                    <th>To Branch</th>
                    <td><?= htmlspecialchars($transaction['to_branch_id']) ?></td>
                    <td><?= htmlspecialchars($to_branch['name']) ?></td>
                    <td><?= htmlspecialchars($to_branch['city']) ?></td>
                    <td><?= htmlspecialchars($to_branch['address']) ?></td>
                </tr>
                <tr>
                    <th>Date Start</th>
                    <td colspan="4"><?= htmlspecialchars($transaction['date_start']) ?></td>
                </tr>
                <tr>
                    <th>Date End</th>
                    <td colspan="4"><?= htmlspecialchars($transaction['date_end']) ?></td>
                </tr>
                <tr>
                    <th>Arrive Status</th>
                    <td colspan="4" id="status-<?= htmlspecialchars($transaction['registration_number']) ?>"><?= $transaction['arrive_confirm'] == 1 ? 'Arrived' : 'Not Arrived' ?></td>
                </tr>
                <?php if ($transaction['arrive_confirm'] == 0 && is_manager()) { ?>
                    <tr>
                        <th>Action</th>
                        <td colspan="4">
                            <form method="post">
                                <input type="hidden" name="registration_number" value="<?= htmlspecialchars($transaction['registration_number']) ?>">
                                <button type="submit" class="btn btn-primary">
                                    Mark as Arrived
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php }
                if (is_admin()) { ?>
                    <tr>
                        <th>Action</th>
                        <td colspan="4">
                            <form method="post">
                                <input type="hidden" name="registration_number" value="<?= htmlspecialchars($transaction['registration_number']) ?>">
                                <button type="submit" class="btn btn-primary">
                                    Mark as <?= $transaction['arrive_confirm'] == 0 ? 'Arrived' : 'Not Arrived' ?>
                                </button>
                            </form>
                        </td>
                    </tr>


                <?php } ?>
                <tr>
                    <td colspan="5">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProduct" aria-expanded="false" aria-controls="collapseProduct">
                                Show Products
                            </button>
                            <div class="collapse mt-2" id="collapseProduct">
                                <div class="card card-body">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">Product Id</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Product Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($products_shipment as $p) { ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($p["product_id"]) ?></td>
                                                    <td><?= htmlspecialchars($productController->getById($p["product_id"])["name"]) ?></td>
                                                    <td><?= htmlspecialchars($p["quantity"]) ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php } ?>
</div>