<?php

require_once 'model.php';

Class Controller_Home extends Model
{

    function index()
    {
        var_dump(time()); die();
        if (isset($_GET['id']) && $_GET['id'] > 0)
        {
            if (isset($_GET['page']) && $_GET['page'] == 'material')
            {
                $where = array(
                    'id' => $_GET['id']
                );
                $material = $this->select($where, 'materials');
                if (!empty($material))
                {
                    $where = array(
                        'id' => $material['author_id']
                    );
                    $user = $this->select($where, 'users');
                    
                    $where = array(
                        'id' => $material['modified_by']
                    );
                    $mod_user = $this->select($where, 'users');
                }
            }
            if (isset($_GET['page']) && $_GET['page'] == 'category')
            {
                $where = array(
                    'id' => $_GET['id'],
                    'status' => 1,
                    'trash' => 0
                );
                $category = $this->select($where, 'categories');
                if (!empty($category))
                {
                    $where = array(
                        'id' => $category['author_id']
                    );
                    $user = $this->select($where, 'users');

                    $where = array(
                        'category_id' => $_GET['id'],
                        'status' => 1,
                        'trash' => 0 . ' "AND start_publication <='.time().' AND end_publication >'.time(),
                    );
                    $material_list = $this->select($where, 'materials');
                }
            }
        }
        else
        {
            $where = array(
                'status' => 1,
                'trash' => 0,
                'for_index' => '1 AND start_publication <='.time().' AND end_publication >'.time(),
            );
            $items_menu = $this->select($where, 'items_menu');

            if (!empty($items_menu))
            {
                $this->redirect($items_menu['path']);
            }
            else
            {
                $where = array(
                    'status' => 1,
                    'trash' => 0,
                    'favorite' => '1 AND start_publication <='.time().' AND end_publication >'.time(),
                );
                $material_list = $this->select($where, 'materials');
                if (!empty($material_list))
                {
                    $category = TRUE;
                }
            }
        }
        $array = $this->front_menu();
        $menu_name = $array[0];
        $items_menu = $array[1];

        $title = 'Главная';
        require 'front/header.php';
        require 'front/left.php';
        require 'front/content.php';
        require 'front/footer.php';
    }

    function front()
    {
        $where = array(
            'trash' => 0,
            'status' => 1
        );
        $materials = $this->select($where, 'materials');

        $array = $this->front_menu();
        $menu_name = $array[0];
        $items_menu = $array[1];

        $title = 'Главная';
        require 'front/header.php';
        require 'front/left.php';
        require 'front/index.php';
        require 'front/footer.php';
    }

}

?>
