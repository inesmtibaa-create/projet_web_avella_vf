<?php
session_start();
require_once 'autoload.php';
$nom=$_POST['nom'];
$prix=$_POST['prix'];
$id=$_POST['boutique_id'];
$categorie=(int)$_POST['categorie_id'];
$desc=$_POST['description'];
$newfilepath = null;
if (!empty($_FILES['image']['name'])) {
    $newfilepath = "../files/" . uniqid() . "_" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], $newfilepath);
}
$produit=new produits();
$params=['categorie_id'=>$categorie,'nom'=>$nom,'description'=>$desc,'prix'=>$prix,'image'=>$newfilepath,'boutique_id'=>$id];
$produit->insert($params);
header('location:admin.php');




