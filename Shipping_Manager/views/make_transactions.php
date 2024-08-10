<?php
$branchController = new BranchController();
$productController = new ProductController();
$stockController = new StockController();
$transactionController = new TransactionController();
$shipmentController = new ShipmentController();
$branch_id = isset($_GET["branch_id"]) ? $_GET["branch_id"] : null;

if ($branch_id != null) {
    $products_id = $stockController->getAllBy("branch_id = " . $branch_id . " AND quantity > 0");
    $ids = array_column($products_id, 'product_id');
    $quantities = array_column($products_id, 'quantity', 'product_id');
}

$all_branches = $branchController->getAll();
$all_products = $productController->getAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $to_branch_id = isset($_POST['to_branch_id']) ? $_POST['to_branch_id'] : null;
    $date_start = isset($_POST['date_start']) ? $_POST['date_start'] : null;
    $date_end = isset($_POST['date_end']) ? $_POST['date_end'] : null;
    $products = isset($_POST['products']) ? json_decode($_POST['products'], true) : [];


    if ($to_branch_id && $date_start && $date_end && count($products) > 0) {

        do {
            $registration_number = generateRandomString();
            $check = DB::checkIfExist($transactionController->getBy("registration_number = '$registration_number'"));
        } while ($check != 0);


        $transactionController->create(array(
            "registration_number" => $registration_number,
            "from_branch_id" => $branch_id,
            "to_branch_id" => $to_branch_id,
            "date_start" => $date_start,
            "date_end" => $date_end,
        ));

        foreach ($products as $p) {
            $shipmentController->create(array(
                "registration_number" => $registration_number,
                "product_id" => $p["id"],
                "quantity" => $p["quantity"],
            ));

            $currentStock = $stockController->getAllBy("branch_id = $branch_id AND product_id = " . $p['id']);
            $newQuantity = $currentStock[0]["quantity"] - $p["quantity"];
            $stockController->update(array("quantity" => $newQuantity), $currentStock[0]["id"]);
        }

        exit();
    } else {
        echo "Please select a branch, both dates, and at least one product.";
    }
}
?>

<div class="container">
    <h2>Make Transactions</h2>

    <select class="form-select mt-2" id="branchSelect">
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
                <select class="form-select" id="productSelect">
                    <option value="">Select product</option>
                    <?php foreach ($all_products as $product) {
                        if (in_array($product["id"], $ids)) { ?>
                            <option value="<?= $product["id"] ?>" data-quantity="<?= $quantities[$product['id']] ?>">
                                <?= $product["name"] ?>
                            </option>
                    <?php }
                    } ?>
                </select>
            </div>
            <div class="col">
                <input type="number" class="form-control" id="quantityInput" min="1" placeholder="Quantity">
            </div>
            <div class="col">
                <button class="btn btn-primary" id="addButton">Add</button>
            </div>
        </div>

        <div class="mt-3">
            <h3>Product List</h3>
            <table class="table" id="productTable">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div class="card card-body mt-3">
            <select class="form-select" id="toBranchSelect">
                <option value="">Select another branch</option>
                <?php foreach ($all_branches as $branch) {
                    if ($branch["id"] != $branch_id) { ?>
                        <option value="<?= $branch["id"] ?>">
                            <?= $branch["city"] . " | " . $branch["name"] . " | " . $branch["address"] ?>
                        </option>
                <?php }
                } ?>
            </select>
            <div class="row mt-3">
                <div class="col">
                    <p>Start From</p>
                    <input type="date" class="form-control" id="date_start" name="date_start">
                </div>
                <div class="col">
                    <P>To End</P>
                    <input type="date" class="form-control" id="date_end" name="date_end">
                </div>
            </div>
            <button class="btn btn-primary mt-3" id="submitDates">Submit</button>
        </div>
    <?php } ?>
</div>

<script>
    document.getElementById('branchSelect').addEventListener('change', function() {
        var branchId = this.value;
        var url = new URL(window.location.href);
        url.searchParams.set('branch_id', branchId);
        window.location.href = url.href;
    });

    document.getElementById('productSelect').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var maxQuantity = selectedOption.getAttribute('data-quantity');
        var quantityInput = document.getElementById('quantityInput');
        quantityInput.max = maxQuantity;
        quantityInput.value = "";
    });

    const addedProducts = new Set();

    document.getElementById('addButton').addEventListener('click', function() {
        var productSelect = document.getElementById('productSelect');
        var selectedOption = productSelect.options[productSelect.selectedIndex];
        var productId = selectedOption.value;
        var productName = selectedOption.text;
        var quantityInput = document.getElementById('quantityInput');
        var quantity = quantityInput.value;
        var maxQuantity = selectedOption.getAttribute('data-quantity');

        if (!productId) {
            alert('Please select a product.');
            return;
        }

        if (addedProducts.has(productId)) {
            alert('This product has already been added.');
            return;
        }

        if (!quantity || Number(quantity) <= 0 || Number(quantity) > Number(maxQuantity)) {
            alert(`Please enter a valid quantity (1 - ${maxQuantity}).`);
            return;
        }

        var productTable = document.getElementById('productTable').getElementsByTagName('tbody')[0];
        var newRow = productTable.insertRow();

        var cell1 = newRow.insertCell(0);
        var cell2 = newRow.insertCell(1);
        var cell3 = newRow.insertCell(2);
        var cell4 = newRow.insertCell(3);

        cell1.textContent = productId;
        cell2.textContent = productName;
        cell3.textContent = quantity;

        var deleteButton = document.createElement('button');
        deleteButton.className = 'btn btn-danger';
        deleteButton.textContent = 'Delete';
        deleteButton.onclick = function() {
            productTable.deleteRow(newRow.rowIndex - 1);
            addedProducts.delete(productId);
        };

        cell4.appendChild(deleteButton);

        // Add product to the set of added products
        addedProducts.add(productId);

        // Clear the inputs
        productSelect.selectedIndex = 0;
        quantityInput.value = "";
        quantityInput.max = "";
    });

    document.getElementById('submitDates').addEventListener('click', function() {
        var toBranchSelect = document.getElementById('toBranchSelect');
        var toBranchId = toBranchSelect.value;
        var dateStart = document.getElementById('date_start').value;
        var dateEnd = document.getElementById('date_end').value;

        var productTable = document.getElementById('productTable').getElementsByTagName('tbody')[0];
        if (productTable.rows.length === 0) {
            alert('Please add at least one product to the list.');
            return;
        }

        if (!toBranchId || !dateStart || !dateEnd) {
            alert('Please select a branch and both dates.');
            return;
        }

        var products = [];
        for (var i = 0; i < productTable.rows.length; i++) {
            var row = productTable.rows[i];
            var productId = row.cells[0].textContent;
            var quantity = row.cells[2].textContent;
            products.push({
                id: productId,
                quantity: quantity
            });
        }

        var formData = new FormData();
        formData.append('to_branch_id', toBranchId);
        formData.append('date_start', dateStart);
        formData.append('date_end', dateEnd);
        formData.append('products', JSON.stringify(products));

        fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                window.location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>