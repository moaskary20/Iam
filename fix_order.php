<?php
$pdo = new PDO('sqlite:database/database.sqlite');

// Update the order values to be different
$stmt1 = $pdo->prepare('UPDATE sliders SET `order` = ? WHERE id = ?');
$stmt1->execute([1, 6]); // First slider (ID: 6) order = 1
$stmt1->execute([2, 7]); // Second slider (ID: 7) order = 2

echo "Updated slider orders:\n";
echo "Slider ID 6 (re) - order set to 1\n";
echo "Slider ID 7 (76) - order set to 2\n";

// Verify the update
$stmt = $pdo->query('SELECT id, title, `order`, active FROM sliders ORDER BY `order`');
echo "\nCurrent slider order:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']}, Title: {$row['title']}, Order: {$row['order']}, Active: {$row['active']}\n";
}
?>
