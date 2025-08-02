<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query('SELECT * FROM sliders ORDER BY `order`');

echo "Checking sliders table:\n";
echo str_repeat("=", 50) . "\n";

if ($stmt) {
    $count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count++;
        echo "Slider #{$count}:\n";
        echo "  ID: {$row['id']}\n";
        echo "  Title: {$row['title']}\n";
        echo "  Image: '{$row['image']}'\n";
        echo "  Active: {$row['active']}\n";
        echo "  Order: {$row['order']}\n";
        echo "  Created: {$row['created_at']}\n";
        echo "  Updated: {$row['updated_at']}\n";
        echo str_repeat("-", 30) . "\n";
    }
    
    if ($count === 0) {
        echo "No sliders found in database.\n";
    }
} else {
    echo "Error: Unable to query sliders table.\n";
}
?>
