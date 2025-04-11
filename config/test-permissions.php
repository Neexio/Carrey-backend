<?php
// Test file permissions and server configuration
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Server Configuration Test</h1>";

// Check PHP version
echo "<h2>PHP Version: " . phpversion() . "</h2>";

// Check required PHP extensions
$required_extensions = array('mysqli', 'curl', 'json', 'mbstring');
echo "<h2>Required Extensions:</h2>";
foreach ($required_extensions as $ext) {
    echo $ext . ": " . (extension_loaded($ext) ? "✓ Loaded" : "✗ Not loaded") . "<br>";
}

// Check file permissions
echo "<h2>File Permissions:</h2>";
$paths = array(
    '.',
    'wp-content',
    'wp-content/plugins',
    'wp-content/uploads'
);

foreach ($paths as $path) {
    if (file_exists($path)) {
        $perms = fileperms($path);
        echo $path . ": " . substr(sprintf('%o', $perms), -4) . "<br>";
    } else {
        echo $path . ": Does not exist<br>";
    }
}

// Check database connection
echo "<h2>Database Connection:</h2>";
try {
    $db = new mysqli('localhost', 'carrfacy_wpq8', 'Eirik16498991', 'carrfacy_wpq8');
    if ($db->connect_error) {
        throw new Exception("Connection failed: " . $db->connect_error);
    }
    echo "Database connection: ✓ Successful<br>";
    echo "Server version: " . $db->server_info . "<br>";
    $db->close();
} catch (Exception $e) {
    echo "Database connection: ✗ Failed - " . $e->getMessage() . "<br>";
}

// Check WordPress constants
echo "<h2>WordPress Constants:</h2>";
$constants = array('ABSPATH', 'WP_CONTENT_DIR', 'WP_PLUGIN_DIR');
foreach ($constants as $constant) {
    echo $constant . ": " . (defined($constant) ? constant($constant) : "Not defined") . "<br>";
} 