<?php
$rootPath = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($rootPath . '/index.php')) {
    require_once $rootPath . '/index.php';
} else {
    require_once '../../../../index.php';
}
