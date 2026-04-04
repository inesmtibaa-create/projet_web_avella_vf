<?php
class categories extends repository {
    public function __construct(){
    return parent::__construct('categories') ;
    }

    public function categorie(){
        $req="select id, nom from categories ";
        $reponse=$this->bd->query($req);
        return($reponse->fetchAll(PDO::FETCH_OBJ));
    }
}