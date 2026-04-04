<?php
abstract class repository implements irepository {
       protected $tablename;
       protected $bd;
       public function __construct($classename){

       $this->tablename=$classename;
       $this->bd=connexion:: getInstance();
       }
    public function selectall(){
      $req="select * from {$this->tablename}";
      $reponse=$this->bd->query($req);
       return ($reponse->fetchAll(PDO::FETCH_ASSOC));


    }
    public function selectid($id){
         $req= "select * from {$this->tablename} where id= ?";
         $reponse=$this->bd->prepare($req);
         $element=$reponse->execute([$id]);
         return $element->fetch(PDO::FETCH_OBJ);

    }
    
    public function finduser($email,$password){
         $req="select * from {$this->tablename} where email=? and password =?";
         $reponse=$this->bd->prepare($req);
         $reponse->execute([$email,$password]);
         return $reponse->fetchAll(PDO::FETCH_OBJ);}
    public function insert($params){
        $keys=array_keys($params);
        $keystring=implode(',',$keys);
        $paramstring=implode(',',array_fill(0,count($keys),'?'));
        $query="insert into {$this->tablename} (id,{$keystring}) values (null,{$paramstring})";
        $reponse=$this->bd->prepare($query);
        $reponse->execute(array_values($params));
    }
    public function delete($id){
      $req="delete from {$this->tablename} where id=?";
      $reponse=$this->bd->prepare($req);
      $reponse->execute([(int) $id]);
    }
    public function update($id,$params){
       $keys=array_keys($params);
       $string=implode('=? ,' , $keys);
       $req="Update {$this->tablename} set {$string} where id=?";
       $reponse=$this->bd->prepare($req);
       $reponse->execute(array_values($params));



    } 
    /* fonction qui retourne le role du user */
    public function return_role($email,$mdp)
     { $req="select role from {$this->tablename} where email=? and password =?";
     $reponse=$this->bd->prepare($req);
     $reponse->execute([$email,$mdp]);
     $result = $reponse->fetch(PDO::FETCH_OBJ); 
     return $result->role ?? null;
        }
        /* count les lignes d'une table */
   public function count(){
         $req="select count(*) as total from {$this->tablename}";
         $reponse=$this->bd->prepare($req);
          $reponse->execute([]);
          return $reponse->fetch(PDO::FETCH_OBJ)->total ?? 0;
          
        }
        /*filtrer d'aprés un critère */
       
       
       
}