<?php
session_start();
require_once 'autoload.php';


if (empty($_POST['email']) || empty($_POST['password'])) {
    header('location: connexion.html?erreur=champs');
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $_POST['email']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($_POST['password'], $user['password'])) {
    header('location: connexion.html?erreur=incorrect');
    exit();
}


$_SESSION['user_id'] = $user['id'];
$_SESSION['role']    = $user['role'];
$_SESSION['nom']     = $user['nom'];

if ($user['role'] === 'vendeur') {
    header('location: seller-dashboard.html');
} else if ($user['role'] === 'admin') {
    header('location: dashboard-admin.php');
} else {
    header('location: seller-dashboard.html');
}
exit();