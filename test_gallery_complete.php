<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== COMPREHENSIVE GALLERY DEBUG ===\n\n";

// 1. Database check
echo "1. DATABASE CHECK:\n";
$galleries = \App\Models\Gallery::all();
echo "   Total galleries: " . count($galleries) . "\n";
foreach($galleries as $g) {
    $status = $g->is_active ? 'ACTIVE' : 'INACTIVE';
    $hasImage = !empty($g->image_path) ? 'YES' : 'NO';
    echo "   - ID {$g->id}: {$g->title} [{$status}] Image: {$hasImage}\n";
}

// 2. Active galleries filter
echo "\n2. ACTIVE GALLERIES (scopeFilter):\n";
$active = \App\Models\Gallery::active()->get();
echo "   Count: " . count($active) . "\n";
foreach($active as $g) {
    echo "   - {$g->title}\n";
}

// 3. Database response with image_url
echo "\n3. TOARRAY() WITH IMAGE_URL:\n";
$arrayData = \App\Models\Gallery::active()->get()->toArray();
echo "   Items: " . count($arrayData) . "\n";
foreach($arrayData as $idx => $item) {
    $imgStatus = isset($item['image_url']) ? 'HAS' : 'MISSING';
    echo "   [{$idx}] {$item['title']} - image_url: {$imgStatus}\n";
}

// 4. API Response
echo "\n4. API RESPONSE (/api/gallery):\n";
$controller = new \App\Http\Controllers\DataController();
$response = $controller->getGalleryItems();
$body = $response->getContent();
$items = json_decode($body, true);
echo "   JSON Items: " . count($items) . "\n";
for($i = 0; $i < min(count($items), 4); $i++) {
    $item = $items[$i];
    $img = isset($item['image_url']) && !empty($item['image_url']) ? substr($item['image_url'], 0, 50) . '...' : 'NULL/EMPTY';
    echo "   [{$i}] {$item['title']}\n";
    echo "       image_url: {$img}\n";
}

// 5. CSS Check
echo "\n5. CSS / LAYOUT:\n";
echo "   Desktop (>1024px): 3 items per view\n";
echo "   - calc((100% - 2 * 2rem) / 3) ≈ 31% per item\n";
echo "   - With gap 2rem: item width calc\n";

// 6. Frontend Formula
echo "\n6. JAVASCRIPT CALCULATION (4 items, 3 per view):\n";
echo "   ItemWidth (assuming 300px each): ~300px\n";
echo "   Gap: 32px (2rem)\n";
echo "   Offset for index 1: -(1 * 300) - (1 * 32) = -332px\n";
echo "   Should show items 1,2,3 at start\n";
echo "   Should show items 2,3,4 on next click\n";

echo "\n=== END DEBUG ===\n";
