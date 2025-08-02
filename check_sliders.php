<?php
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->boot();

$sliders = \App\Models\Slider::all();

echo "Total sliders: " . $sliders->count() . "\n";

foreach ($sliders as $slider) {
    echo "ID: {$slider->id}\n";
    echo "Title: {$slider->title}\n";
    echo "Image: {$slider->image}\n";
    echo "Active: {$slider->active}\n";
    echo "Order: {$slider->order}\n";
    echo "---\n";
}
