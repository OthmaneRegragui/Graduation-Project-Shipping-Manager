<?php


// Function to get all directions without BASE_URL
function getDirectionsWithoutBaseUrl($items, $baseUrl)
{
    $directions = [];
    foreach ($items as $item) {
        if ($item->direction !== '#') {
            $directions[] = str_replace($baseUrl, '', $item->direction);
        }
        if (!empty($item->subItems)) {
            $subDirections = getDirectionsWithoutBaseUrl($item->subItems, $baseUrl);
            $directions = array_merge($directions, $subDirections);
        }
    }
    return $directions;
}

function generateRandomString()
{
    $randomNumbers = mt_rand(10000000000, 99999999999); // Generate a random 11-digit number
    return "T-" . $randomNumbers;
}

function check_authentication()
{
    if (empty(Session::get("username"))) {
        echo "<script>window.location.href = '" .  BASE_URL . "login" . "';</script>";
    }
}

function is_admin()
{
    $role = Session::get("role");
    if (!empty($role) && $role == "admin") {
        return true;
    }
    return false;
}

function is_manager()
{
    $role = Session::get("role");
    if (!empty($role) && $role == "manager") {
        return true;
    }
    return false;
}

function is_employee()
{
    $role = Session::get("role");
    if (!empty($role) && $role == "employee") {
        return true;
    }
    return false;
}

function redirect_to_home()
{
    echo "<script>window.location.href = '" .  BASE_URL . "home" . "';</script>";
}
