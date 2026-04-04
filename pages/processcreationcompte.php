<?php
session_start();
require_once 'db.php';

$stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role, telephone) 
                        VALUES (:nom, :prenom, :email, :password, :role, :telephone)");

$stmt->execute([
    ':nom'       => $_POST['name'],
    ':prenom'    => $_POST['prenom'],
    ':email'     => $_POST['email'],
    ':password'  => password_hash($_POST['password'], PASSWORD_DEFAULT),
    ':role'      => $_POST['role'],
    ':telephone' => $_POST['num']
]);

$userId = $pdo->lastInsertId(); 
$_SESSION['user_id'] = $userId;
$_SESSION['role']    = $_POST['role'];

if ($_POST['role'] === 'vendeur') {
    header('location: vendeur-infos.html');
} else {
    header('location: succes-de-creation.html');
}
exit();