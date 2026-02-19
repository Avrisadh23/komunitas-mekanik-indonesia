<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST API GALLERY ===\n\n";

// Check 1: Database galleries
$galleries = \App\Models\Gallery::where('is_active', true)->get();
echo "1. DATABASE:\n";
echo "   Total active galleries: " . count($galleries) . "\n";
foreach($galleries as $g) {
    echo "   - ID " . $g->id . ": " . $g->title . "\n";
    echo "     image_url: " . ($g->image_url ? 'HAS' : 'MISSING') . "\n";
}

// Check 2: API Response via controller
echo "\n2. CONTROLLER RESPONSE:\n";
if (count($galleries) > 0) {
    $controller = new \App\Http\Controllers\DataController();
    $response = $controller->getGalleryItems();
    $items = json_decode($response->getContent(), true);
    echo "   Items returned: " . count($items) . "\n";
    if (count($items) > 0) {
        echo "   First item: " . $items[0]['title'] . "\n";
        echo "   Has image_url: " . (isset($items[0]['image_url']) ? 'YES' : 'NO') . "\n";
    }
}

// Check 3: Test fetch URL
echo "\n3. SIMULATED FETCH:\n";
ob_start();
echo json_encode($galleries->toArray());
$json = ob_get_clean();
echo "   JSON length: " . strlen($json) . " bytes\n";
echo "   Valid JSON: " . (json_last_error() === JSON_ERROR_NONE ? 'YES' : 'NO') . "\n";

echo "\n=== END TEST ===\n";
