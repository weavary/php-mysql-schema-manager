<?php
if (!function_exists('firelog')) {
    function firelog($obj, $namespace = 'debug') {
        require_once 'vendors' . DIRECTORY_SEPARATOR . 'FirePHPCore' . DIRECTORY_SEPARATOR . 'FirePHP.class.php';

        $firephp = FirePHP::getInstance(true);
        $firephp->log($obj, $namespace);
    }
}
