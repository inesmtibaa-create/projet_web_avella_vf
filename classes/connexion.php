<?php

class connexion{
 private static $_bdd=null;
 private static $_dbname = "avella_db";
 private static $_user   = "root";
 private static $_pwd    = "Chahd21*12*2005";
 private static $_host   = "127.0.0.1";
 private static $_port ="3306";
 private function __construct()
 {
    try{
        self::$_bdd=new PDO("mysql:host=".self::$_host.
";port="      . self::$_port .";dbname=".self::$_dbname.";charset=utf8",
self::$_user, self::$_pwd
      );

    }
    catch(PDOException $e){
          echo "base not found";
         die('Erreur : '.$e->getMessage());

    }
 }
  public static function getInstance(): PDO {
   if (!self::$_bdd) { new Connexion(); }
   return self::$_bdd;
 }


}
?>