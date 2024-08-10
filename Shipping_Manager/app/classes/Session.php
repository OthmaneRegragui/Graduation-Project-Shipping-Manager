<?php

class Session {
    // Start a new session or resume an existing session
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Set a session variable
    public static function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    // Get a session variable
    public static function get($name) {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    // Check if a session variable is set
    public static function exists($name) {
        return isset($_SESSION[$name]);
    }

    // Remove a session variable
    public static function delete($name) {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    // Destroy the entire session
    public static function destroy() {
        session_unset();
        session_destroy();
    }
}
