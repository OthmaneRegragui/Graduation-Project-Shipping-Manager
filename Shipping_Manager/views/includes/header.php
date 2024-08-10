<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Manager</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">

</head>

<body>
    <?php


    $navbar = new Navbar($navbarItems);
    ?>

    <!-- Sidebar -->
    <div class="sidebar d-none d-md-block">
        <nav class="nav flex-column">
            <?php $navbar->generate(); ?>
        </nav>
    </div>

    <!-- Top Navbar for Mobile -->
    <nav class="navbar navbar-expand-md navbar-light bg-light d-md-none">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php $navbar->generate(); ?>
            </ul>
        </div>
    </nav>

    <!-- Page content -->
    <div class="content">