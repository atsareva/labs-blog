<?php

interface Database
{

    public function getCollection();
    
    public function load($id);

    public function insert($data);

    public function update($data);

    public function delete($where);

}

?>
