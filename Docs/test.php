<?php
$file = __DIR__ . '/Classes/Networking/test.txt';
if (file_put_contents($file, "Hello!") !== false) {
    echo "Write successful!";
} else {
    echo "Cannot write to folder!";
}
?>
