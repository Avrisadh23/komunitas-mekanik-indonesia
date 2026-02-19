<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check galleries
$galleries = \App\Models\Gallery::all();
echo "=== GALLERIES ===\n";
echo "Total: " . count($galleries) . "\n\n";
foreach($galleries as $g) {
    echo "ID: {$g->id}, Title: {$g->title}, Active: " . ($g->is_active ? 'YES' : 'NO') . "\n";
}

echo "\n=== ACTIVE GALLERIES (via filter) ===\n";
$active = \App\Models\Gallery::active()->get();
echo "Active Count: " . count($active) . "\n";
foreach($active as $g) {
    echo "- {$g->title}\n";
}

// Test API response
echo "\n=== API RESPONSE ===\n";
$controller = new \App\Http\Controllers\DataController();
$response = $controller->getGalleryItems();
echo $response->getContent();
