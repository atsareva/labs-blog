<?php

class Helper_Admin
{

    public function getMenuArray()
    {
        return Core::getModel('menu')
                        ->addFieldToFilter(array('id', 'title'))
                        ->addFieldToFilter('trash', array('=' => 0))
                        ->getCollection()
                        ->getData();
    }

}

