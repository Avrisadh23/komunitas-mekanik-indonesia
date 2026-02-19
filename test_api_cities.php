<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST API ENDPOINT ===\n\n";

// Test controller method directly
$controller = new \App\Http\Controllers\BengkelController();

echo "1. getCitiesByProvince('Jawa Barat'):\n";
$response = $controller->getCitiesByProvince('Jawa Barat');
echo "   Response content: " . $response->getContent() . "\n";
$cities = json_decode($response->getContent(), true);
echo "   Decoded: " . json_encode($cities) . "\n";
echo "   Type: " . gettype($cities) . "\n";
if (is_array($cities)) {
    echo "   Count: " . count($cities) . "\n";
}

echo "\n2. getCitiesByProvince('Jawa Tengah'):\n";
$response2 = $controller->getCitiesByProvince('Jawa Tengah');
echo "   Response content: " . $response2->getContent() . "\n";

echo "\n3. getCitiesByProvince('Invalid Province'):\n";
$response3 = $controller->getCitiesByProvince('Invalid Province');
echo "   Response content: " . $response3->getContent() . "\n";

echo "\n=== END TEST ===\n";
