<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


header('Content-Type: application/json');
require_once 'autoload.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = (int) $data['id'];

$produit = new produits();
$row = $produit->selectid($id); // retourne un objet/tableau avec les infos

echo json_encode(['success' => true, 'produit' => $row]);