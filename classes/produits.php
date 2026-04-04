<?php
class produits extends repository {
    public function __construct(){
    return parent::__construct('produits') ;
    }
public function products_info(){
$req = "select p.id,p.nom,c.nom as categorie,p.description ,p.prix,p.image
  from produits p,categories c where c.id=p.categorie_id" ;
 $reponse = $this->bd->prepare($req);
 $reponse->execute();
 return $reponse->fetchAll(PDO::FETCH_OBJ);
    }
}