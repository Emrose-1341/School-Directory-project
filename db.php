<?php
function load_json($filename) {
    $path = __DIR__ . '/' . $filename;
    if (!file_exists($path)) {
        die("JSON file not found: $path");
    }
    $data = json_decode(file_get_contents($path), true);
    if ($data === null) {
        die("Failed to decode JSON: $filename");
    }
    return $data;
}

$users = load_json('users.json');
