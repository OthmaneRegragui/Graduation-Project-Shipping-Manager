<?php
/* Show errors */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './bootstrap.php';
require_once './autoload.php';


/* Nav bar menu */
if (!isset($_GET["page"]) || $_GET["page"] == "login") {
    $navbarItems = [];
} else {
    $navbarItems = [
        new NavbarItem(Session::get("name"), ""),
        new NavbarItem('Home', BASE_URL . "home"),
    ];
    $branchNav = [];
    $transactionsNav = [];


    if (is_admin()) {
        $navbarItems[] = new NavbarItem('Employees', '#', [
            new NavbarItem('Add New employee', BASE_URL . "add_new_employee"),
            new NavbarItem('Manage employees', BASE_URL . "manage_employees"),
        ]);
        $branchNav[] = new NavbarItem('Add new branche', BASE_URL . "add_new_branche");
    }
    if (!is_employee()) {
        $branchNav[] = new NavbarItem('Manage branches', BASE_URL . "manage_branches");
        $transactionsNav[] = new NavbarItem('Show transactions', BASE_URL . 'show_transactions');
        $transactionsNav[] = new NavbarItem('Make transactions', BASE_URL . "make_transactions");
    }
    $branchNav[] = new NavbarItem('Manage Stock', BASE_URL . "manage_stock");
    $navbarItems[] = new NavbarItem('Branches', '#', $branchNav);


    $transactionsNav[] = new NavbarItem('Track transactions', BASE_URL . "track_transactions");
    $navbarItems[] = new NavbarItem('Transactions', '#', $transactionsNav);

    $navbarItems[] = new NavbarItem('Logout', BASE_URL . "logout");
}



$home = new HomeController();
$directions = getDirectionsWithoutBaseUrl($navbarItems, BASE_URL);
if (!is_employee()) {
    $directions = array_merge(["add_employee_to_branch", "manage_employee_branch"], $directions);
}
$pages = array_merge(['home', "add_employee_to_branch", "manage_employee_branch", "login", "logout"], $directions);

if (!isset($_GET["page"]) || $_GET["page"] == "login") {
    $selected_page = "login";
} else {
    check_authentication();
    $selected_page = $_GET["page"];
}
require_once './views/includes/header.php';



if (isset($selected_page)) {
    if (in_array($selected_page, $pages)) {
        $page = $selected_page;
        $home->index($page);
    } else {
        include('views/includes/404.php');
    }
} else {
    $home->index('login');
}
require_once './views/includes/footer.php';
