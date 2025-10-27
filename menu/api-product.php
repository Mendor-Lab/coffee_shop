<?php
header('Content-Type: application/json');

$menuType = $_GET['type'] ?? 'coffees';
$filePath = __DIR__ . "/../data/menu/" . $menuType . ".json";

if (file_exists($filePath)) {
  echo file_get_contents($filePath);
} else {
  echo json_encode(["error" => "Menu type not found"]);
}
?>
