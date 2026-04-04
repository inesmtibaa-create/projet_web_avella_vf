<?php
class produits extends repository {
    public function __construct(){
    return parent::__construct('produits') ;
    }
public function products_info(){
$req = "select p.id,p.nom,c.nom as categorie,p.description ,p.prix,p.image,b.nom as boutique
  from produits p,categories c,boutiques b where c.id=p.categorie_id and b.id=p.boutique_id" ;
 $reponse = $this->bd->prepare($req);
 $reponse->execute();
 return $reponse->fetchAll(PDO::FETCH_OBJ);
    }
}