<?php

require_once 'model.php';
require_once 'controller_favourite.php';

class Controller_Content extends Model
{

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['user']) || empty($_SESSION['user']))
        {
            header('Location: http://' . $this->BASE_URL . '/auth/login');
        }
    }

    public function index($id=NULL)
    {
        var_dump($id);
        echo $id . 'page index';
    }

    public function create_page()
    {
        require_once 'page.php';
    }

    public function create()
    {
        if (isset($_POST) && !empty($_POST))
        {
            $data = array(
                'title' => $_POST['title'],
                'alias' => $_POST['alias'],
                'category_id' => $_POST['category_id'],
                'status' => $_POST['status'],
                'favorite' => $_POST['favorite'],
                'full_text' => $_POST['content'],
                'intro_text' => $_POST['intro_text'],
            );
            (!empty($_POST['author_id'])) ? $data['author_id'] = $_POST['author_id'] : $data['author_id'] = $_SESSION['user']['id'];

            (!empty($_POST['author_pseudo'])) ? $data['author_pseudo'] = $_POST['author_pseudo'] : $data['author_pseudo'] = NULL;

            (!empty($_POST['created'])) ? $data['created'] = strtotime($_POST['created']) : $data['created'] = time();

            (!empty($_POST['start_publication'])) ? $data['start_publication'] = strtotime($_POST['start_publication']) : $data['start_publication'] = time();

            (!empty($_POST['end_publication'])) ? $data['end_publication'] = strtotime($_POST['end_publication']) : $data['end_publication'] = 0;

            (!empty($_POST['description'])) ? $data['description'] = $_POST['description'] : $data['description'] = NULL;

            (!empty($_POST['keywords'])) ? $data['keywords'] = $_POST['keywords'] : $data['keywords'] = NULL;

            if (isset($_POST['id_material']) && (int) $_POST['id_material'] == 0)
            {
                $id_material = $this->insert($data, 'materials');
                $where = array(
                    'id' => $id_material
                );
            }
            else
            {
                $data['modified'] = time();
                $data['modified_by'] = $_SESSION['user']['id'];


                $data['id'] = (int) $_POST['id_material'];

                $where = array(
                    'id' => (int) $_POST['id_material']
                );
                $result_ver = $this->select($where, 'materials');

                if ($result_ver)
                {
                    $data['version'] = $result_ver['version'] + 1;
                }

                $data = array_reverse($data);
                $result = $this->update($data, 'materials');
                if ($result)
                {
                    $where = array(
                        'id' => (int) $_POST['id_material']
                    );
                }
            }

            $result = $this->select($where, 'materials');

            $where = array(
                'id' => $result['author_id']
            );

            $user = $this->select($where, 'users');

            if (isset($_POST['save_exit']) && $_POST['save_exit'] == 1)
            {
                if (isset($_SESSION['favourite']) && $_SESSION['favourite'] == TRUE)
                {
                    header('Location: http://' . $this->BASE_URL . '/admin/favourite');
                }
                else
                {
                    header('Location: http://' . $this->BASE_URL . '/admin/content');
                }
            }
            else
            {
                $category = $this->load_collection('categories');

                if (isset($_POST['edit_material']) && $_POST['edit_material'] != 0)
                {
                    $edit = TRUE;
                    $title = "Редактировать материал";
                }
                else
                {
                    $title = "Создать материал";
                }
                $menu_content = TRUE;

                $query = "SELECT id, title FROM menu WHERE trash!=1";
                $admin_menu = $this->sql($query);

                require 'head.php';
                require 'admin/menu.php';
                require_once 'admin/material/create.php';
                require 'footer.php';
            }
        }
        else
        {
            $category = $this->load_collection('categories');

            if (empty($category))
            {
                $category = FALSE;
            }
            require_once 'admin/material/create.php';
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
                $result = $this->select($where, 'materials');

                $where = array(
                    'id' => $result['author_id']
                );

                $user = $this->select($where, 'users');
            }
            $category = $this->load_collection('categories');

            $edit = TRUE;
            $title = "Редактировать материал";
            require_once 'admin/material/create.php';
        }
        else
        {
            if (isset($_SESSION['favourite']) && $_SESSION['favourite'] == TRUE)
            {
                header('Location: http://' . $this->BASE_URL . '/admin/favourite');
            }
            else
            {
                header('Location: http://' . $this->BASE_URL . '/admin/content');
            }
        }
    }

    public function public_material()
    {
        $data = $_POST['data'];
        $bool = $_POST['bool'];
        $array = explode(',', $data);
        if (is_array($array) && !empty($array))
        {
            foreach ($array as $value)
            {
                if ($bool)
                {
                    $material = array(
                        'id' => (int) $value,
                        'status' => 1
                    );
                }
                else
                {
                    $material = array(
                        'id' => (int) $value,
                        'status' => 0
                    );
                }
                $this->update($material, 'materials');
            }
        }
        if (isset($_SESSION['favourite']) && $_SESSION['favourite'] == TRUE)
        {
            $obj = new Controller_Favourite();
            $data = $obj->load_material();
            unset($obj);
        }
        else
        {
            $data = $this->load_material();
        }
        $title = "Менеджер материалов";
        $menu_material = TRUE;

        require 'admin/material.php';
    }

    public function favorite_material()
    {
        $data = $_POST['data'];
        $array = explode(',', $data);
        if (is_array($array) && !empty($array))
        {
            foreach ($array as $value)
            {
                if (isset($_POST['radio']) && $_POST['radio'] == 1)
                {
                    $where = array(
                        'id' => $value
                    );
                    $result = $this->select($where, 'materials');

                    if ($result['favorite'])
                    {
                        $material = array(
                            'id' => (int) $value,
                            'favorite' => 0
                        );
                    }
                    else
                    {
                        $material = array(
                            'id' => (int) $value,
                            'favorite' => 1
                        );
                    }
                }
                else
                {
                    $material = array(
                        'id' => (int) $value,
                        'favorite' => 1
                    );
                }
                $this->update($material, 'materials');
            }
        }
        if (isset($_SESSION['favourite']) && $_SESSION['favourite'] == TRUE)
        {
            $obj = new Controller_Favourite();
            $data = $obj->load_material();
            unset($obj);
        }
        else
        {
            $data = $this->load_material();
        }

        $title = "Менеджер материалов";
        $menu_material = TRUE;

        require 'admin/material.php';
    }

    public function trash_material()
    {
        $data = $_POST['data'];

        $array = explode(',', $data);
        if (is_array($array) && !empty($array))
        {
            foreach ($array as $value)
            {
                $material = array(
                    'id' => (int) $value,
                    'trash' => 1
                );
                $this->update($material, 'materials');
            }
        }
        if (isset($_SESSION['favourite']) && $_SESSION['favourite'] == TRUE)
        {
            $obj = new Controller_Favourite();
            $data = $obj->load_material();
            unset($obj);
        }
        else
        {
            $data = $this->load_material();
        }

        $title = "Менеджер материалов";
        $menu_material = TRUE;

        require 'admin/material.php';
    }

    public function load_material()
    {
        $result = $this->load_collection('materials');
        if (!empty($result) && is_array($result))
        {
            if (isset($result[0]) && is_array($result[0]))
            {
                foreach ($result as $value)
                {
                    ($value['status'] == 0) ? $status = "Не опубликовано" : $status = "Опубликовано";

                    if ($value['category_id'] == 0)
                    {
                        $category = "Без категории";
                    }
                    else
                    {
                        $where = array(
                            'id' => $value['category_id']
                        );
                        $category = $this->select($where, 'categories');
                        $category = $category['title'];
                    }

                    $sql = "SELECT user_name FROM users WHERE id='{$value['author_id']}'";
                    $result_sql = $this->sql($sql);

                    $data[] = array(
                        'id' => $value['id'],
                        'title' => $value['title'],
                        'status' => $status,
                        'favorite' => $value['favorite'],
                        'category' => $category,
                        'author' => $result_sql['user_name'],
                        'data' => date('d.m.Y', $value['created'])
                    );
                }
            }
            elseif (!empty($result) && is_array($result))
            {
                //var_dump($result);die();
                ($result['status'] == 0) ? $status = "Не опубликовано" : $status = "Опубликовано";

                if ($result['category_id'] == 0)
                {
                    $category = "Без категории";
                }
                else
                {
                    $where = array(
                        'id' => $result['category_id']
                    );
                    $category = $this->select($where, 'categories');
                    $category = $category['title'];
                }

                $sql = "SELECT user_name FROM users WHERE id='{$result['author_id']}'";
                $result_sql = $this->sql($sql);

                $data = array(
                    'id' => $result['id'],
                    'title' => $result['title'],
                    'status' => $status,
                    'favorite' => $result['favorite'],
                    'category' => $category,
                    'author' => $result_sql['user_name'],
                    'data' => date('d.m.Y', $result['created'])
                );
            }
            else
            {
                $data = NULL;
            }
            return $data;
        }
        else
        {
            return FALSE;
        }
    }

}

?>
