<?php
// On cPanel, the path might be different depending on where public_html is
// We assume index.php is in the root (one level above public/)
$rootPath = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($rootPath . '/index.php')) {
    require_once $rootPath . '/index.php';
} else {
    // Fallback if structure is nested differently
    require_once '../../../../index.php';
}
