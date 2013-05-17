<?php

class Helper_User
{

    public function getCurrentUser()
    {
        $user = Core::getModel('user')
                ->addFieldToFilter('session_id', array('=' => session_id()))
                ->getCollection()
                ->getData();
        if (isset($user[0]->id))
            return $user[0]->id;
        return false;
    }

    public function getUserInfo()
    {
        $id = $this->getCurrentUser();
        if ($id)
        {
            $user = Core::getModel('user')
                    ->addFieldToFilter(array('id', 'user_name', 'full_name', 'access_id', 'photo'))
                    ->load($id);
            return $user;
        }
        return false;
    }

}

