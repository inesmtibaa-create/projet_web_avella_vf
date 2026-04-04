<?php
session_start();
require_once 'db.php';

// Upload photo
$newfilepath = null;
if (!empty($_FILES['photo']['name'])) {
    $newfilepath = "files/" . uniqid() . "_" . $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], $newfilepath);
}

$stmt = $pdo->prepare("INSERT INTO boutiques (user_id, nom, description) 
                        VALUES (:user_id, :nom, :description)");

$stmt->execute([
    ':user_id'     => $_SESSION['user_id'], 
    ':nom'         => $_POST['nomProjet'],
    ':description' => $_POST['description']
]);

header('location: succes-de-creation.php');
exit();