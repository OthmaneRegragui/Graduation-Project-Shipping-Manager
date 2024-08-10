<?php
session_start();
require_once "./bootstrap.php";

spl_autoload_register('autoload');

function autoload($class_name){
    $array_paths = array(
        'database/',
        'app/classes/',
        'models/',
        'controllers/',
        'utilities/'
    );

    $parts = explode('\\', $class_name);
    $name = array_pop($parts);

    foreach ($array_paths as $path) {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($regex as $file) {
            require_once $file[0];
        }
    }
}
?>
