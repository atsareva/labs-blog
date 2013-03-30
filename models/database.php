<?php

interface Database
{

    public function load_collection($table);
    
    public function select($where, $table);

    public function insert($data, $table);

    public function update($data, $table);

    public function delete($where, $table);

}

?>
