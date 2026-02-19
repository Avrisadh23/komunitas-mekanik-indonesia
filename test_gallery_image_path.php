<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== GALLERY IMAGE PATHS ===\n\n";

$galleries = \App\Models\Gallery::all();
foreach($galleries as $g) {
    echo "ID " . $g->id . ": " . $g->title . "\n";
    echo "  image_path: " . ($g->image_path ?: '(null/empty)') . "\n";
    echo "  image_url: " . ($g->image_url ?: '(null)') . "\n";
    echo "\n";
}

echo "=== END ===\n";
