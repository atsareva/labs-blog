<?php

require_once 'model.php';

class Controller_Users extends Model
{

    public function __construct()
    {
        parent::__construct();
        if ((!isset($_SESSION['user']) || empty($_SESSION['user'])) || $_SESSION['user']['access_id'] < 3)
        {
            header('Location: http://' . $this->BASE_URL . '/auth/login');
        }
    }

    public function load_users()
    {
        $query = "SELECT id, user_name, full_name, status_id, block, email, access_id FROM users WHERE access_id != 1 AND id != {$_SESSION['user']['id']}";
        $users = $this->sql($query);

        if (!empty($users))
        {
            if (isset($users[0]) && is_array($users[0]))
            {
                foreach ($users as $key => $value)
                {
                    switch ($value['status_id'])
                    {
                        case 1:
                            $users[$key]['status_id'] = 'преподаватель';
                            break;
                        case 2:
                            $users[$key]['status_id'] = 'студент';
                            break;
                        case 3:
                            $users[$key]['status_id'] = 'администратор';
                            break;
                    }
                    $where = array(
                        'id' => $value['access_id']
                    );
                    $access = $this->select($where, 'access');
                    $users[$key]['access_id'] = $access['description'];
                    if ($access['title'] == 'for_admin' || $access['title'] == 'for_superadmin')
                        $admin[$value['id']] = $access['id'];
                }
            }
            else
            {
                switch ($users['status_id'])
                {
                    case 1:
                        $users['status_id'] = 'преподаватель';
                        break;
                    case 2:
                        $users['status_id'] = 'студент';
                        break;
                    case 3:
                        $users['status_id'] = 'администратор';
                        break;
                }
                $where = array(
                    'id' => $users['access_id']
                );
                $access = $this->select($where, 'access');
                $users['access_id'] = $access['description'];
                if ($access['title'] == 'for_admin' || $access['title'] == 'for_superadmin')
                {
                    $admin[$users['id']] = $access['id'];
                }
            }

            if (!isset($admin))
            {
                $admin = FALSE;
            }
            return array($users, $admin);
        }
        return FALSE;
    }

    public function create()
    {

        if (isset($_POST) && !empty($_POST))
        {
            $data = array(
                'user_name'     => $_POST['login'],
                'pass'          => md5($_POST['pass']),
                'email'         => $_POST['email'],
                'register_date' => time(),
                'last_login'    => time()
            );

            ($_POST['status_id'] == 0) ? $data['status_id'] = 2 : $data['status_id'] = $_POST['status_id'];

            ($_POST['access_id'] == 0) ? $data['access_id'] = 4 : $data['access_id'] = $_POST['access_id'];

            if (isset($_POST['id_user']) && (int) $_POST['id_user'] == 0)
            {
                $id_user = $this->insert($data, 'users');
                $where = array(
                    'id' => $id_user
                );
            }
            else
            {
                $data['id'] = (int) $_POST['id_user'];
                $data = array_reverse($data);
                $result = $this->update($data, 'users');
                if ($result)
                {
                    $where = array(
                        'id' => (int) $_POST['id_user']
                    );
                }
            }

            $result = $this->select($where, 'users');


            if (isset($_POST['save_exit']) && $_POST['save_exit'] == 1)
            {
                header('Location: http://' . $this->BASE_URL . '/admin/users');
            }
            else
            {
                $query = "SELECT * FROM access";
                $access = $this->sql($query);

                $query = "SELECT * FROM user_status";
                $status = $this->sql($query);

                if (isset($_POST['edit_user']) && $_POST['edit_user'] != 0)
                {
                    $edit = TRUE;
                    $title = "Редактировать пользователя";
                }
                else
                {
                    $title = "Создать пользователя";
                }
                $menu_users = TRUE;

                $query = "SELECT id, title FROM menu WHERE trash!=1";
                $admin_menu = $this->sql($query);

                require 'head.php';
                require 'admin/menu.php';
                require_once 'admin/user/create.php';
                require 'footer.php';
            }
        }
        else
        {
            $query = "SELECT * FROM access";
            $access = $this->sql($query);

            $query = "SELECT * FROM user_status";
            $status = $this->sql($query);

            require_once 'admin/user/create.php';
        }
    }

    public function edit()
    {
        if (isset($_POST) && !empty($_POST))
        {
            if (isset($_POST['id_edit']))
            {
                $where = array(
                    'id' => $_POST['id_edit']
                );
                $result = $this->select($where, 'users');

                $query = "SELECT * FROM access";
                $access = $this->sql($query);

                $query = "SELECT * FROM user_status";
                $status = $this->sql($query);
            }
            else
            {
                
            }
            $edit = TRUE;
            $title = "Редактировать пользователя";
            require_once 'admin/user/create.php';
        }
        else
        {
            header('Location: http://' . $this->BASE_URL . '/admin/users');
        }
    }

    public function block_user()
    {
        $id = $_POST['id'];

        $where = array(
            'id' => $id
        );
        $result = $this->select($where, 'users');

        if (isset($result) && !empty($result))
        {
            if ($result['block'])
            {
                $user = array(
                    'id' => (int) $id,
                    'block' => 0
                );
            }
            else
            {
                $user = array(
                    'id' => (int) $id,
                    'block' => 1
                );
            }
            $this->update($user, 'users');
        }

        $array = $this->load_users();
        $data = $array[0];
        $admin = $array[1];

        $title = "Пользователи";
        $menu_users = TRUE;

        require 'admin/users.php';
    }

}

?>
