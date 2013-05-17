<?php

require_once CORE_PATH . 'model/model' . EXT;

class Model_User extends Model
{

    public function setTable()
    {
        $this->_tableName = 'users';
    }

    /**
     * Load users
     *
     * @param int $accessCurrentUser
     * @return array
     */
    public function loadUsers($accessCurrentUser)
    {
        if ($accessCurrentUser == 5)
            return $this->addFieldToFilter('users.*')
                            ->join('user_status', 'user_status.id = users.status_id', 'user_status.name AS status_id', 'left')
                            ->join('access', 'access.id = users.access_id', 'access.description AS access')
                            ->getCollection()->getData();
        else
            return $this->addFieldToFilter('users.*')
                            ->addFieldToFilter('access_id', array('<' => '4'))
                            ->join('user_status', 'user_status.id = users.status_id', 'user_status.name AS status_id', 'left')
                            ->join('access', 'access.id = users.access_id', 'access.description AS access')
                            ->getCollection()->getData();
    }

}

?>
