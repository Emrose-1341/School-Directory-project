<?php
$baseDir = "C:\\Users\\project_acc\\Classes"; // Root directory
$path = isset($_GET['path']) ? $_GET['path'] : "";

// Prevent directory traversal attacks
$fullPath = realpath($baseDir . DIRECTORY_SEPARATOR . $path);
if ($fullPath === false || strpos($fullPath, realpath($baseDir)) !== 0) {
    die("Invalid path.");
}

// Get list of files and folders
$items = scandir($fullPath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Explorer</title>
</head>
<body>
    <h1>Browsing: <?php echo htmlspecialchars($fullPath); ?></h1>

    <?php if ($fullPath != realpath($baseDir)): ?>
        <p><a href="?path=<?php echo urlencode(dirname($path)); ?>">⬅ Go up</a></p>
    <?php endif; ?>

    <ul>
        <?php
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            $itemPath = $path ? $path . DIRECTORY_SEPARATOR . $item : $item;
            if (is_dir($fullPath . DIRECTORY_SEPARATOR . $item)) {
                echo "<li>[DIR] <a href='?path=" . urlencode($itemPath) . "'>" . htmlspecialchars($item) . "</a></li>";
            } else {
                echo "<li>[FILE] <a href='download.php?file=" . urlencode($itemPath) . "'>" . htmlspecialchars($item) . "</a></li>";
            }
        }
        ?>
    </ul>
</body>
</html>
