<?php

$transactionController = new TransactionController();
$branchController = new BranchController();

// Fetch all transactions
$transactions = $transactionController->getAll();

// Optional: Filter transactions based on arrive status if a filter is applied
$filterArriveStatus = isset($_GET['arrive_status']) ? $_GET['arrive_status'] : '';

if ($filterArriveStatus !== '') {
    $transactions = array_filter($transactions, function ($transaction) use ($filterArriveStatus) {
        return $transaction['arrive_confirm'] == $filterArriveStatus;
    });
}
?>

<div class="container mt-5">
    <div class="row mb-3">
        <div class="col">
            <form method="get">
                <select class="form-select" name="arrive_status" aria-label="Arrive status" onchange="this.form.submit()">
                    <option value="" <?= $filterArriveStatus === '' ? 'selected' : '' ?>>All Arrive Status</option>
                    <option value="1" <?= $filterArriveStatus === '1' ? 'selected' : '' ?>>Arrived</option>
                    <option value="0" <?= $filterArriveStatus === '0' ? 'selected' : '' ?>>Not Arrived</option>
                </select>
            </form>
        </div>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Registration Number</th>
                <th>From Branch</th>
                <th>To Branch</th>
                <th>Date Start</th>
                <th>Date End</th>
                <th>Arrive Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $transaction) { ?>
                <tr>
                    <td><?= htmlspecialchars($transaction['registration_number']) ?></td>
                    <td><?= htmlspecialchars($branchController->getById($transaction['from_branch_id'])['name']) ?></td>
                    <td><?= htmlspecialchars($branchController->getById($transaction['to_branch_id'])['name']) ?></td>
                    <td><?= htmlspecialchars($transaction['date_start']) ?></td>
                    <td><?= htmlspecialchars($transaction['date_end']) ?></td>
                    <td><?= $transaction['arrive_confirm'] == 1 ? 'Arrived' : 'Not Arrived' ?></td>
                    <td>
                        <form method="get" action="track_transactions" target="_blank">
                            <input type="hidden" name="registration_number" value="<?= htmlspecialchars($transaction['registration_number']) ?>">
                            <button type="submit" class="btn btn-primary">Track</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>