<?php
// Test PHP error suppression
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

echo "Testing PHP error suppression...\n";

// Test with a simple deprecation warning simulation
echo "Test completed.\n";