<?php

interface Database
{

    public function getCollection();
    
    public function select($where);

    public function insert($data);

    public function update($data);

    public function delete($where);

}

?>
