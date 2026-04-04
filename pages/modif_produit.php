<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'autoload.php';

$id        = (int) $_POST['id'];
$nom       = $_POST['nom'];
$prix      = $_POST['prix'];
$idb       = $_POST['boutique_id'];
$categorie = (int) $_POST['categorie_id'];
$desc      = $_POST['description'];

$newfilepath = null;
if (!empty($_FILES['image']['name'])) {
    $newfilepath = "../files/" . uniqid() . "_" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $newfilepath);
}

$produit = new produits();
$params  = [
    'categorie_id' => $categorie,
    'nom'          => $nom,
    'description'  => $desc,
    'prix'         => $prix,
    'boutique_id'  => $idb,
];
if ($newfilepath) $params['image'] = $newfilepath;

$produit->update($id, $params);
header('Location: admin.php?success=1');
exit();