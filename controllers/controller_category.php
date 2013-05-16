<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Category extends Controller_A
{

    public function __construct()
    {
        parent::__construct();
    }

    public function publicCategory()
    {
        $ids  = explode(',', trim($_POST['ids'], ','));
        $bool = $_POST['bool'];

        foreach ($ids as $id)
            Core::getModel('category')->addFieldToFilter(array('id', 'status'))->load((int) $id)->setData('status', $bool)->save();
    }

    public function remove()
    {
        $ids = explode(',', trim($_POST['ids'], ','));
        foreach ($ids as $id)
            Core::getModel('category')->addFieldToFilter(array('id', 'trash'))->load((int) $id)->setTrash(1)->save();
    }

    public function create()
    {
        if (isset($_POST) && !empty($_POST))
        {
            $data              = array(
                'title'     => $_POST['title'],
                'alias'     => $_POST['alias'],
                'status'    => $_POST['status'],
                'full_text' => $_POST['content'],
                'created'   => time()
            );
            (!empty($_POST['author_id'])) ? $data['author_id'] = $_POST['author_id'] : $data['author_id'] = $_SESSION['user']['id'];

            (!empty($_POST['description'])) ? $data['description'] = $_POST['description'] : $data['description'] = NULL;

            (!empty($_POST['keywords'])) ? $data['keywords'] = $_POST['keywords'] : $data['keywords'] = NULL;

            if (isset($_POST['id_category']) && (int) $_POST['id_category'] == 0)
            {
                $id_category = $this->insert($data, 'categories');
                $where       = array(
                    'id' => $id_category
                );
            }
            else
            {
                $data['id'] = (int) $_POST['id_category'];
                $data       = array_reverse($data);
                $result     = $this->update($data, 'categories');
                if ($result)
                {
                    $where = array(
                        'id' => (int) $_POST['id_category']
                    );
                }
            }

            $result = $this->select($where, 'categories');

            $where = array(
                'id' => $result['author_id']
            );

            $user = $this->select($where, 'users');

            if (isset($_POST['save_exit']) && $_POST['save_exit'] == 1)
            {
                header('Location: http://' . $this->BASE_URL . '/admin/category');
            }
            else
            {
                if (isset($_POST['edit_category']) && $_POST['edit_category'] != 0)
                {
                    $edit  = TRUE;
                    $title = "Редактировать категорию";
                }
                else
                {
                    $title         = "Создать категорию";
                }
                $menu_category = TRUE;

                $query      = "SELECT id, title FROM menu WHERE trash!=1";
                $admin_menu = $this->sql($query);

                require 'head.php';
                require 'admin/menu.php';
                require_once 'admin/category/create.php';
                require 'footer.php';
            }
        }
        else
        {
            require_once 'admin/category/create.php';
        }
    }

    public function edit()
    {
        if (isset($_POST) && !empty($_POST))
        {
            if (isset($_POST['id_edit']))
            {
                $where  = array(
                    'id' => $_POST['id_edit']
                );
                $result = $this->select($where, 'categories');

                $where = array(
                    'id' => $result['author_id']
                );

                $user = $this->select($where, 'users');
            }
            else
            {
                
            }
            $edit  = TRUE;
            $title = "Редактировать категорию";
            require_once 'admin/category/create.php';
        }
        else
        {
            header('Location: http://' . $this->BASE_URL . '/admin/category');
        }
    }

    public function public_category()
    {
        $data  = $_POST['data'];
        $bool  = $_POST['bool'];
        $array = explode(',', $data);
        if (is_array($array) && !empty($array))
        {
            foreach ($array as $value)
            {
                if ($bool)
                {
                    $material = array(
                        'id'     => (int) $value,
                        'status' => 1
                    );
                }
                else
                {
                    $material = array(
                        'id'     => (int) $value,
                        'status' => 0
                    );
                }
                $this->update($material, 'categories');
            }
        }
        $data     = $this->load_category();

        $title         = "Менеджер категорий";
        $menu_category = TRUE;

        require 'admin/category.php';
    }

    public function trash_category()
    {
        $data = $_POST['data'];

        $array = explode(',', $data);
        if (is_array($array) && !empty($array))
        {
            foreach ($array as $value)
            {
                $material = array(
                    'id'    => (int) $value,
                    'trash' => 1
                );
                $this->update($material, 'categories');
            }
        }
        $data     = $this->load_category();

        $title         = "Менеджер категорий";
        $menu_category = TRUE;

        require 'admin/category.php';
    }

    public function load_category()
    {
        $result = $this->load_collection('categories');
        //var_dump($result);die();
        if (!empty($result) && is_array($result))
        {
            if (isset($result[0]) && is_array($result[0]))
            {
                foreach ($result as $value)
                {
                    ($value['status'] == 0) ? $status = "Не опубликовано" : $status = "Опубликовано";

                    $sql        = "SELECT user_name FROM users WHERE id='{$value['author_id']}'";
                    $result_sql = $this->sql($sql);

                    $data[] = array(
                        'id'     => $value['id'],
                        'title'  => $value['title'],
                        'status' => $status,
                        'author' => $result_sql['user_name'],
                        'data'   => date('d.m.Y', $value['created'])
                    );
                }
            }
            elseif (!empty($result) && is_array($result))
            {
                //var_dump($result);die();
                ($result['status'] == 0) ? $status = "Не опубликовано" : $status = "Опубликовано";

                $sql        = "SELECT user_name FROM users WHERE id='{$result['author_id']}'";
                $result_sql = $this->sql($sql);

                $data = array(
                    'id'     => $result['id'],
                    'title'  => $result['title'],
                    'status' => $status,
                    'author' => $result_sql['user_name'],
                    'data'   => date('d.m.Y', $result['created'])
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
