<?php
function loadEnv($file = '.env') {
    if (!file_exists($file)) {
        throw new Exception("File .env không tồn tại.");
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Bỏ qua dòng comment
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}
