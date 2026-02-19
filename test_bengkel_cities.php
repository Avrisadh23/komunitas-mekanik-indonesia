<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DEBUG BENGKEL CITIES API ===\n\n";

// 1. Check bengkels in database
echo "1. BENGKELS IN DATABASE:\n";
$allBengkels = \App\Models\Bengkel::all();
echo "   Total: " . count($allBengkels) . "\n";

$byProvince = [];
foreach($allBengkels as $b) {
    if (!isset($byProvince[$b->province])) {
        $byProvince[$b->province] = 0;
    }
    $byProvince[$b->province]++;
}

foreach($byProvince as $prov => $count) {
    echo "   - $prov: $count bengkels\n";
}

// 2. Test getCitiesByProvince function
echo "\n2. GETCITIESBYPROVINCE FUNCTION:\n";
$jawaBarat = \App\Models\Bengkel::getCitiesByProvince('Jawa Barat');
echo "   Result for 'Jawa Barat': ";
if (is_array($jawaBarat)) {
    echo "Array with " . count($jawaBarat) . " cities\n";
    foreach($jawaBarat as $city) {
        echo "   - $city\n";
    }
} else {
    echo "Not an array! Type: " . gettype($jawaBarat) . "\n";
    var_dump($jawaBarat);
}

// 3. Test Active bengkels filter
echo "\n3. ACTIVE BENGKELS:\n";
$activeBengkels = \App\Models\Bengkel::active()->get();
echo "   Total active: " . count($activeBengkels) . "\n";
echo "   By province:\n";
$activeByProv = [];
foreach($activeBengkels as $b) {
    if (!isset($activeByProv[$b->province])) {
        $activeByProv[$b->province] = 0;
    }
    $activeByProv[$b->province]++;
}
foreach($activeByProv as $prov => $count) {
    echo "   - $prov: $count\n";
}

// 4. Direct test with scope
echo "\n4. TEST SCOPE:\n";
$jawaBarat2 = \App\Models\Bengkel::active()
    ->where('province', 'Jawa Barat')
    ->distinct()
    ->pluck('city')
    ->sort();
echo "   Cities: ";
var_dump($jawaBarat2->toArray());

echo "\n=== END DEBUG ===\n";
