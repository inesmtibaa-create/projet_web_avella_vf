<?php
interface irepository{
    public function selectall();
    public function selectid($id);
    public function delete($id);
    public function finduser($email,$password);
    public function insert($params);
    public function update($id,$params); 


}