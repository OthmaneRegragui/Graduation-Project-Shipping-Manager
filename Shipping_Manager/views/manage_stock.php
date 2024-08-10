<?php
$branchController = new BranchController();
$productController = new ProductController();
$stockController = new StockController();

$branch_id = isset($_GET["branch_id"]) ? $_GET["branch_id"] : null;
$product_id = isset($_GET["product_id"]) ? $_GET["product_id"] : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'addProduct') {
        $name = $_POST['name'];
        $length = $_POST['length'];
        $width = $_POST['width'];
        $height = $_POST['height'];

        $productController->create(array(
            "name" => $name,
            "length" => $length,
            "width" => $width,
            "height" => $height
        ));
    } elseif ($_POST['action'] == 'raiseQuantity' && $branch_id && $product_id) {
        $quantity = intval($_POST['quantity']);
        $currentStock = $stockController->getAllBy("branch_id = $branch_id AND product_id = $product_id");

        if ($quantity > 0) {
            if (count($currentStock) != 0) {
                $newQuantity = $currentStock[0]["quantity"] + $quantity;
                $stockController->update(array("quantity" => $newQuantity), $currentStock[0]["id"]);
            } else {
                $stockController->create(array("branch_id" => $branch_id, "product_id" => $product_id, "quantity" => $quantity));
            }
        }
    } elseif ($_POST['action'] == 'decreaseQuantity' && $branch_id && $product_id) {
        $quantity = intval($_POST['quantity']);
        $currentStock = $stockController->getAllBy("branch_id = $branch_id AND product_id = $product_id");

        if ($quantity > 0 && count($currentStock) != 0) {
            $newQuantity = $currentStock[0]["quantity"] - $quantity;
            if ($newQuantity >= 0) {
                $stockController->update(array("quantity" => $newQuantity), $currentStock[0]["id"]);
            }
        }
    }
}

$all_branches = $branchController->getAll();
$all_products = $productController->getAll();
?>

<div class="container">
    <h2>Manage Stock</h2>
    <div class="row">
        <div class="col">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
                Add Product
            </button>
        </div>
    </div>
    <select class="form-select mt-2" aria-label="Default select example" id="branchSelect">
        <option <?= (!isset($branch_id) ? "selected" : "") ?> value="">Select branch</option>
        <?php foreach ($all_branches as $branch) { ?>
            <option <?= ($branch_id == $branch["id"]) ? "selected" : "" ?> value="<?= $branch["id"] ?>">
                <?= $branch["city"] . " | " . $branch["name"] . " | " . $branch["address"] ?>
            </option>
        <?php } ?>
    </select>

    <?php if ($branch_id != null) { ?>
        <div class="row mt-3">
            <div class="col">
                <select class="form-select" aria-label="Default select example" id="productSelect">
                    <option <?= (!isset($product_id) ? "selected" : "") ?> value="">Select Product</option>
                    <?php foreach ($all_products as $product) { ?>
                        <option <?= ($product_id == $product["id"]) ? "selected" : "" ?> value="<?= $product["id"] ?>">
                            <?= $product["name"] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </div>
    <?php } ?>

    <?php
    if ($branch_id && $product_id) {
        $results = $stockController->getAllBy(" branch_id = $branch_id AND product_id = $product_id");

        // Fetch product details
        $product = $productController->getById($product_id);
        $currentQuantity = count($results) == 0 ? 0 : $results[0]["quantity"];
    ?>
        <div class="row mt-3">
            <div class="col">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">Name</th>
                            <td><?= $product["name"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Length</th>
                            <td><?= $product["length"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Width</th>
                            <td><?= $product["width"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Height</th>
                            <td><?= $product["height"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Quantity</th>
                            <td><?= $currentQuantity ?></td>
                        </tr>
                    </tbody>
                </table>

                <?php if (is_admin()) { ?>

                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#raiseQuantityForm" aria-expanded="false" aria-controls="raiseQuantityForm">
                        Raise Quantity
                    </button>
                <?php } ?>
                <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target="#decreaseQuantityForm" aria-expanded="false" aria-controls="decreaseQuantityForm">
                    Decrease Quantity
                </button>
                <div class="collapse mt-2" id="raiseQuantityForm">
                    <div class="card card-body">
                        <form method="post">
                            <input type="hidden" name="action" value="raiseQuantity">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
                <div class="collapse mt-2" id="decreaseQuantityForm">
                    <div class="card card-body">
                        <form method="post">
                            <input type="hidden" name="action" value="decreaseQuantity">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <button type="submit" class="btn btn-danger">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" method="post">
                    <input type="hidden" name="action" value="addProduct">
                    <div class="mb-3">
                        <label for="productName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="productLength" class="form-label">Length</label>
                        <input type="number" step="0.01" class="form-control" id="productLength" name="length" required>
                    </div>
                    <div class="mb-3">
                        <label for="productWidth" class="form-label">Width</label>
                        <input type="number" step="0.01" class="form-control" id="productWidth" name="width" required>
                    </div>
                    <div class="mb-3">
                        <label for="productHeight" class="form-label">Height</label>
                        <input type="number" step="0.01" class="form-control" id="productHeight" name="height" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('branchSelect').addEventListener('change', function() {
        var branchId = this.value;
        var url = new URL(window.location.href);
        url.searchParams.set('branch_id', branchId);
        window.location.href = url.href;
    });

    <?php if ($branch_id != null) { ?>
        document.getElementById('productSelect').addEventListener('change', function() {
            var productId = this.value;
            var url = new URL(window.location.href);
            url.searchParams.set('product_id', productId);
            window.location.href = url.href;
        });
    <?php } ?>
</script>