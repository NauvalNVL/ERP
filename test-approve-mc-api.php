<?php
// Simple script to test the Approve MC API endpoint

// Configuration
$apiEndpoint = 'http://erp.test/api/approve-mc';
$csrfToken = ''; // You'll need to get this from your browser

// Test data
$testData = [
    'mc_seq' => 'MC-TEST-' . rand(1000, 9999),
    'mc_model' => 'Test Model ' . date('Y-m-d H:i:s'),
    'customer_code' => 'CUST001',
    'customer_name' => 'Test Customer',
    'status' => 'pending'
];

// cURL setup
$ch = curl_init($apiEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'X-Requested-With: XMLHttpRequest',
    'X-CSRF-TOKEN: ' . $csrfToken
]);

// Execute request
echo "Sending request to $apiEndpoint with data:\n";
echo json_encode($testData, JSON_PRETTY_PRINT) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($error = curl_error($ch)) {
    echo "cURL Error: " . $error . "\n";
} else {
    echo "HTTP Status Code: " . $httpCode . "\n";
    echo "Response:\n";
    
    // Try to prettify JSON
    $decoded = json_decode($response);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode($decoded, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo $response . "\n";
    }
}

curl_close($ch); 