<?php
require_once 'autoload.php';
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$type = $_POST['type'] ?? '';
$id   = $_POST['id']   ?? 0;

if ($type === 'produit') {
    $prod = new produits();
    $result = $prod->delete($id);
    echo json_encode(['success' => $result]);
    exit();
} else if ($type === 'boutique') {
    $bout = new boutiques();
 $result = $bout->delete($id);
 echo json_encode(['success' => $result]);
}
 else if ($type === 'categorie') {
   $cat = new categorie();
$result = $cat->delete($id);
echo json_encode(['success' => $result]);
}


else{
     echo json_encode(['success' => false, 'message' => 'Type inconnu']);
}