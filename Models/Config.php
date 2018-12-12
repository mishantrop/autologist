<?php
namespace Logisting;

class Config
{
    private static $_instance = null;
    private $config;

    private function __construct()
    {
        $this->config = json_decode(file_get_contents('config.json'), true);
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Self;
        }

        return self::$_instance;
    }

    public function get($key)
    {
        return isset($this->config[$key]) ? $this->config[$key] : null;
    }

    private function __clone() {}
    private function __wakeup() {}

    public function __destruct()
    {
        self::$_instance = null;
    }
}