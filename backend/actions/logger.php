<?php
function log_stock_debug($msg) {
    $logFile = __DIR__ . '/../../stock_debug.log';
    $timestamp = date("Y-m-d H:i:s") . "." . isset(explode(".", microtime(true))[1]) ? explode(".", microtime(true))[1] : "000";
    $entry = "[$timestamp] " . $msg . PHP_EOL;
    file_put_contents($logFile, $entry, FILE_APPEND);
}
?>
