<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Menu extends Controller_A
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create()
    {
        /**
         * @todo Validation
         */
        if (isset($_POST) && !empty($_POST))
        {
            $data = $_POST;
            unset($data['save_exit']);

            $menu = Core::getModel('menu')->setData($data)->save();

            $saveExit = $_POST['save_exit'];
            unset($_POST);

            if ($saveExit)
                $this->redirect('admin/menu');
            else
                $this->redirect('menu/edit/' . $menu->getId());
        }

        $head    = 'Создать меню';
        $menuUrl = Core::getBaseUrl() . 'menu/create';

        $statuses = Core::getModel('user_status')->getCollection()->getData();
        $access   = Core::getModel('access')->getAccess();

        $this->_view->setTitle('Создать меню')
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuMenu' => true))
                ->setChild('content', 'admin/menu/form_menu', array('head'     => $head, 'menuUrl'  => $menuUrl, 'statuses' => $statuses, 'access'   => $access));
    }

    public function edit($id)
    {
        /**
         * @todo Validation
         */
        if (isset($id[0]))
        {
            $menu = Core::getModel('menu')->load((int) $id[0]);
            if ($menu->getId())
            {
                if (isset($_POST) && !empty($_POST))
                {
                    $data = $_POST;
                    unset($data['save_exit']);

                    $menu->setData($data)->save();

                    $saveExit = $_POST['save_exit'];
                    unset($_POST);

                    if ($saveExit)
                        $this->redirect('admin/menu');
                    else
                        $this->redirect('menu/edit/' . $menu->getId());
                }

                $head    = 'Редактировать меню';
                $menuUrl = Core::getBaseUrl() . 'menu/edit/' . $menu->getId();

                $statuses = Core::getModel('user_status')->getCollection()->getData();
                $access   = Core::getModel('access')->getAccess();

                $this->_view->setTitle('Редактировать меню')
                        ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuMenu' => true))
                        ->setChild('content', 'admin/menu/form_menu', array('head'     => $head, 'menu'     => $menu, 'menuUrl'  => $menuUrl, 'statuses' => $statuses, 'access'   => $access));
            }
            else
                $this->redirect('admin/menu');
        }
        else
            $this->redirect('admin/menu');
    }

    public function remove()
    {
        $ids = explode(',', trim($_POST['ids'], ','));
        foreach ($ids as $id)
            Core::getModel('menu')->load($id)->unsetData()->save();
    }

    public function publicMenu()
    {
        $ids  = explode(',', trim($_POST['ids'], ','));
        $bool = $_POST['bool'];

        foreach ($ids as $id)
            Core::getModel('menu')->load($id)->setData('status', $bool)->save();
    }

    public function create_item()
    {
        if (isset($_POST) && !empty($_POST))
        {
            $data = array(
                'title'     => $_POST['title'],
                'alias'     => $_POST['alias'],
                'status'    => $_POST['status'],
                'menu_id'   => $_POST['menu_id'],
                'parent_id' => $_POST['parent_id'],
                'access_id' => $_POST['access_id'],
                'for_index' => $_POST['for_index'],
            );

            /**
             *  находим родительский элемент dash и сохраням новый эл. с значением dash+1
             */
            $where        = array(
                'id'      => $_POST['parent_id'],
                'menu_id' => $_POST['menu_id'],
            );
            $parent       = $this->select($where, 'items_menu');
            (!empty($parent)) ? $data['dash'] = $parent['dash'] + 1 : $data['dash'] = 1;

            /**
             *  находим родственные элементы, чтобы узнать порядок добавляемого элемента
             */
            $where = array(
                'parent_id' => $_POST['parent_id'],
                'menu_id'   => $_POST['menu_id'],
            );

            $sibling = $this->select($where, 'items_menu');

            if (!isset($_POST['position']))
            {
                if (!empty($sibling))
                {
                    (isset($sibling[0]) && is_array($sibling[0])) ? $position = count($sibling) + 1 : $position = 2;
                }
                else
                {
                    $position         = 1;
                }
                $data['order_of'] = $position;
            }


            /**
             *  находим элементы со значением for_index == 1 и перезаписываем == 0
             */
            if ($_POST['for_index'] == 1)
            {
                $where  = array(
                    'for_index' => 1
                );
                $result = $this->select($where, 'items_menu');
                if (!empty($result))
                {
                    $for_index = array(
                        'id'        => $result['id'],
                        'for_index' => 0
                    );
                    $this->update($for_index, 'items_menu');
                }
            }

            /**
             * если данные были сохранены первый раз
             */
            if (isset($_POST['id_menu_item']) && (int) $_POST['id_menu_item'] == 0)
            {

                if (isset($_POST['path']) && !empty($_POST['path']))
                {
                    $data['path'] = '/' . Config::$DEFULT_CONTROLLER . '?' . $_POST['path'];
                }
                $id_menu_item = $this->insert($data, 'items_menu');
                $where        = array(
                    'id' => $id_menu_item
                );
            }

            /**
             * если данные сохранены повторно, то обновляем уже существующую запись
             */
            else
            {
                if (isset($_POST['path']) && !empty($_POST['path']))
                {
                    if ($_POST['path'] == '/profile/login')
                    {
                        $data['path'] = $_POST['path'];
                    }
                    else
                    {
                        $current_path = explode('?', $_POST['path']);
                        if (count($current_path) == 1)
                        {
                            $data['path'] = '/' . Config::$DEFULT_CONTROLLER . '?' . $current_path[0];
                        }
                        else
                        {
                            $data['path'] = '/' . Config::$DEFULT_CONTROLLER . '?' . $current_path[1];
                        }
                    }
                }
                /**
                 *  находим дочерние элементы и изменяем их уровень доступа
                 */
                $this->access_items($_POST['id_menu_item'], $_POST['access_id']);

                $where          = array(
                    'id' => $_POST['id_menu_item']
                );
                $update_element = $this->select($where, 'items_menu');

                $query = "SELECT * FROM `items_menu` WHERE parent_id = {$update_element['parent_id']} AND order_of > {$update_element['order_of']}";

                $old_sibling = $this->sql($query);

                if (!empty($sibling))
                {
                    if (isset($sibling[0]) && is_array($sibling[0]))
                    {
                        foreach ($sibling as $value)
                        {
                            ($value['id'] == $_POST['id_menu_item']) ? $in_array = TRUE : $in_array = FALSE;
                            if ($in_array == TRUE)
                                break;
                        }

                        if ($in_array)
                        {
                            foreach ($sibling as $value)
                            {

                                if ($value['id'] == $_POST['position'])
                                {
                                    $position   = $value['order_of'];
                                    $id_sibling = $value['id'];
                                }
                                if ($value['id'] == $_POST['id_menu_item'])
                                {
                                    $current_order  = $value['order_of'];
                                }
                            }
                            $update_sibling = array(
                                'id'       => $id_sibling,
                                'order_of' => $current_order
                            );
                            $this->update($update_sibling, 'items_menu');
                        }
                        else
                        {
                            if (!empty($old_sibling))
                            {
                                if (isset($old_sibling[0]) && is_array($old_sibling[0]))
                                {
                                    foreach ($old_sibling as $value)
                                    {
                                        $update_sibling = array(
                                            'id'       => $value['id'],
                                            'order_of' => $value['order_of'] - 1
                                        );
                                        $this->update($update_sibling, 'items_menu');
                                    }
                                }
                                else
                                {
                                    $update_sibling = array(
                                        'id'       => $old_sibling['id'],
                                        'order_of' => $old_sibling['order_of'] - 1
                                    );
                                    $this->update($update_sibling, 'items_menu');
                                }
                            }
                            $position       = count($sibling) + 1;
                        }
                    }
                    else
                    {
                        if (!empty($old_sibling))
                        {
                            if (isset($old_sibling[0]) && is_array($old_sibling[0]))
                            {
                                foreach ($old_sibling as $value)
                                {
                                    $update_sibling = array(
                                        'id'       => $value['id'],
                                        'order_of' => $value['order_of'] - 1
                                    );
                                    $this->update($update_sibling, 'items_menu');
                                }
                            }
                            else
                            {
                                $update_sibling = array(
                                    'id'       => $old_sibling['id'],
                                    'order_of' => $old_sibling['order_of'] - 1
                                );
                                $this->update($update_sibling, 'items_menu');
                            }
                        }
                        ($sibling['id'] == $_POST['id_menu_item']) ? $position       = 1 : $position       = 2;
                    }
                }
                else
                {
                    if (!empty($old_sibling))
                    {
                        if (isset($old_sibling[0]) && is_array($old_sibling[0]))
                        {
                            foreach ($old_sibling as $value)
                            {
                                $update_sibling = array(
                                    'id'       => $value['id'],
                                    'order_of' => $value['order_of'] - 1
                                );
                                $this->update($update_sibling, 'items_menu');
                            }
                        }
                        else
                        {
                            $update_sibling   = array(
                                'id'       => $old_sibling['id'],
                                'order_of' => $old_sibling['order_of'] - 1
                            );
                            $this->update($update_sibling, 'items_menu');
                        }
                    }
                    $position         = 1;
                }
                $data['order_of'] = $position;

                $data['id'] = (int) $_POST['id_menu_item'];
                $data       = array_reverse($data);
                $result     = $this->update($data, 'items_menu');
                if ($result)
                {
                    $where = array(
                        'id' => (int) $_POST['id_menu_item']
                    );
                }
            }

            $result = $this->select($where, 'items_menu');


            $main_menu   = $this->load_collection('menu');
//$child_items = $this->load_collection('items_menu');
            $child_items = $this->load_items($result['menu_id']);

            $where  = "SELECT * FROM access";
            $access = $this->sql($where);

            $where    = array(
                'parent_id' => $result['parent_id'],
                'menu_id'   => $result['menu_id']
            );
            $order_of = $this->select($where, 'items_menu');


            if (isset($_POST['save_exit']) && $_POST['save_exit'] == 1)
            {
                header('Location: http://' . $this->BASE_URL . '/admin/menu?id=' . $result['menu_id']);
            }
            else
            {
                if (isset($_POST['edit_menu_item']) && $_POST['edit_menu_item'] != 0)
                {
                    $edit  = TRUE;
                    $title = "Редактировать пункт меню";
                }
                else
                {
                    $title = "Создать пункт меню";
                }

                $_GET['id'] = $_POST['edit_menu_item'];
                $menu_menu  = TRUE;

                $query      = "SELECT id, title FROM menu WHERE trash!=1";
                $admin_menu = $this->sql($query);

                require 'head.php';
                require 'admin/menu.php';
                require_once 'admin/menu/create_menu_item.php';
                require 'footer.php';
            }
        }
        else
        {
            $main_menu   = $this->load_collection('menu');
//$child_items = $this->load_collection('items_menu');
            $child_items = $this->load_items();

            $where  = "SELECT * FROM access";
            $access = $this->sql($where);

            require_once 'admin/menu/create_menu_item.php';
        }
    }

    public function edit_item_menu()
    {
        if (isset($_POST) && !empty($_POST))
        {
            if (isset($_POST['id_edit']))
            {
                $where  = array(
                    'id' => $_POST['id_edit']
                );
                $result = $this->select($where, 'items_menu');

                $main_menu   = $this->load_collection('menu');
                $child_items = $this->load_items($result['menu_id']);

                $where  = "SELECT * FROM access";
                $access = $this->sql($where);

                $where    = array(
                    'parent_id' => $result['parent_id'],
                    'menu_id'   => $result['menu_id']
                );
                $order_of = $this->select($where, 'items_menu');
            }
            else
            {

            }
            $edit  = TRUE;
            $title = "Редактировать пункт меню";

            $_GET['id'] = $_POST['parent_id'];

            require_once 'admin/menu/create_menu_item.php';
        }
        else
        {
            header('Location: http://' . $this->BASE_URL . '/admin/menu?id=' . $_POST['parent_id']);
        }
    }

    public function access_items($id, $access)
    {
        $menu = $this->load_collection('items_menu');

        $child = $this->menu_tree($menu, $id);

        if (!empty($child))
        {
            foreach ($child as $child_value)
            {
                $data_child = array(
                    'id'        => $child_value['id'],
                    'access_id' => $access
                );
                $this->update($data_child, 'items_menu');
            }
        }
    }

    public function public_menu()
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
                $this->update($material, 'menu');
            }
        }
        $data     = $this->load_menu();
        $count    = $this->count_type_items($data);

        $title     = "Менеджер меню";
        $menu_menu = TRUE;


        require 'admin/content_menu.php';
    }

    public function public_menu_item()
    {
        $data  = $_POST['data'];
        $bool  = $_POST['bool'];
        $array = explode(',', $data);
        if (is_array($array) && !empty($array))
        {
            foreach ($array as $value)
            {
                /**
                 *  загрузка дочерних элементов для рекурсивного изменения статуса
                 */
                $where = array(
                    'menu_id' => $_POST['parent_id']
                );

                $menu = $this->select($where, 'items_menu');

                $child = $this->menu_tree($menu, (int) $value);

                if ($bool)
                {
                    $menu_item = array(
                        'id'     => (int) $value,
                        'status' => 1
                    );
                    if (!empty($child))
                    {
                        foreach ($child as $child_value)
                        {
                            $data_child = array(
                                'id'     => $child_value['id'],
                                'status' => 1
                            );
                            $this->update($data_child, 'items_menu');
                        }
                    }
                }
                else
                {
                    $menu_item = array(
                        'id'     => (int) $value,
                        'status' => 0
                    );

                    if (!empty($child))
                    {

                        foreach ($child as $child_value)
                        {
                            $data_child = array(
                                'id'     => $child_value['id'],
                                'status' => 0
                            );
                            $this->update($data_child, 'items_menu');
                        }
                    }
                }
                $this->update($menu_item, 'items_menu');
            }
        }
        $data       = $this->load_items($_POST['parent_id']);


        $title     = "Менеджер меню";
        $menu_menu = TRUE;

        $_GET['id'] = $_POST['parent_id'];
        require 'admin/menu_item.php';
    }

    public function trash_menu()
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
                $this->update($material, 'menu');
            }
        }
        $data     = $this->load_menu();
        $count    = $this->count_type_items($data);

        $title     = "Менеджер меню";
        $menu_menu = TRUE;

        require 'admin/content_menu.php';
    }

    public function trash_menu_item()
    {
        $data = $_POST['data'];

        $array = explode(',', $data);
        if (is_array($array) && !empty($array))
        {
            foreach ($array as $value)
            {

                /**
                 *  загрузка дочерних элементов для рекурсивного удаления
                 */
                $where = array(
                    'menu_id' => $_POST['parent_id']
                );

                $menu = $this->select($where, 'items_menu');

                $child = $this->menu_tree($menu, (int) $value);

                if (!empty($child))
                {

                    foreach ($child as $child_value)
                    {
                        $data_child = array(
                            'id'    => $child_value['id'],
                            'trash' => 1
                        );
                        $this->update($data_child, 'items_menu');
                    }
                }

                $material = array(
                    'id'    => (int) $value,
                    'trash' => 1
                );
                $this->update($material, 'items_menu');
            }
        }
        $data     = $this->load_items($_POST['parent_id']);


        $title     = "Менеджер меню";
        $menu_menu = TRUE;

        $_GET['id'] = $_POST['parent_id'];
        require 'admin/menu_item.php';
    }

    public function load_menu()
    {
        $result = $this->load_collection('menu');

//var_dump($result);die();
        if (!empty($result) && is_array($result))
        {
            if (isset($result[0]) && is_array($result[0]))
            {
                foreach ($result as $value)
                {
                    ($value['status'] == 0) ? $status = "Не опубликовано" : $status = "Опубликовано";

                    $data[] = array(
                        'id'     => $value['id'],
                        'title'  => $value['title'],
                        'status' => $status
                    );
                }
            }
            elseif (!empty($result) && is_array($result))
            {
//var_dump($result);die();
                ($result['status'] == 0) ? $status = "Не опубликовано" : $status = "Опубликовано";

                $data = array(
                    'id'     => $result['id'],
                    'title'  => $result['title'],
                    'status' => $status
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

    public function for_index()
    {
        $id = $_POST['id'];

        /**
         * ищем элементы, у которых for_index = 1 и устанавливаем значение == 0
         */
        $where  = array(
            'for_index' => 1
        );
        $result = $this->select($where, 'items_menu');
        if (!empty($result))
        {
            $data = array(
                'id'        => $result['id'],
                'for_index' => 0
            );
            $this->update($data, 'items_menu');
        }
        /**
         * устанавливаем значение for_index = 1 новому элементу
         */
        if (empty($result) || $result['id'] != $id)
        {
            $menu_item = array(
                'id'        => $id,
                'for_index' => 1
            );

            $this->update($menu_item, 'items_menu');
        }
        $data = $this->load_items($_POST['parent_id']);


        $title     = "Менеджер меню";
        $menu_menu = TRUE;

        $_GET['id'] = $_POST['parent_id'];
        require 'admin/menu_item.php';
    }

    public function count_type_items($data)
    {
        $count = array();

        if (isset($data[0]) && is_array($data[0]))
        {
            foreach ($data as $menu)
            {

                $where  = array(
                    'menu_id' => $menu['id'],
                    'status'  => 1
                );
                $public = $this->select($where, 'items_menu');
                if (isset($public[0]) && is_array($public[0]))
                {
                    $public = count($public);
                }
                elseif (is_array($public) && !empty($public))
                {
                    $public = 1;
                }
                else
                {
                    $public = 0;
                }

                $where      = array(
                    'menu_id' => $menu['id'],
                    'status'  => 0
                );
                $not_public = $this->select($where, 'items_menu');
                if (isset($not_public[0]) && is_array($not_public[0]))
                {
                    $not_public = count($not_public);
                }
                elseif (is_array($not_public) && !empty($not_public))
                {
                    $not_public = 1;
                }
                else
                {
                    $not_public = 0;
                }


                $where = array(
                    'menu_id' => $menu['id'],
                    'trash'   => 1
                );
                $trash = $this->select($where, 'items_menu');
                if (isset($trash[0]) && is_array($trash[0]))
                {
                    $trash = count($trash);
                }
                elseif (is_array($trash) && !empty($trash))
                {
                    $trash = 1;
                }
                else
                {
                    $trash = 0;
                }

                $count[$menu['id']] = array(
                    'public'     => $public,
                    'not_public' => $not_public,
                    'trash'      => $trash
                );
            }
        }
        else
        {
            $where  = array(
                'menu_id' => $data['id'],
                'status'  => 1
            );
            $public = $this->select($where, 'items_menu');
            if (isset($public[0]) && is_array($public[0]))
            {
                $public = count($public);
            }
            elseif (is_array($public) && !empty($public))
            {
                $public = 1;
            }
            else
            {
                $public = 0;
            }

            $where      = array(
                'menu_id' => $data['id'],
                'status'  => 0
            );
            $not_public = $this->select($where, 'items_menu');
            if (isset($not_public[0]) && is_array($not_public[0]))
            {
                $not_public = count($not_public);
            }
            elseif (is_array($not_public) && !empty($not_public))
            {
                $not_public = 1;
            }
            else
            {
                $not_public = 0;
            }


            $where = array(
                'menu_id' => $data['id'],
                'trash'   => 1
            );
            $trash = $this->select($where, 'items_menu');
            if (isset($trash[0]) && is_array($trash[0]))
            {
                $trash = count($trash);
            }
            elseif (is_array($trash) && !empty($trash))
            {
                $trash = 1;
            }
            else
            {
                $trash = 0;
            }



            $count[$data['id']] = array(
                'public'     => $public,
                'not_public' => $not_public,
                'trash'      => $trash
            );
        }

        return $count;
    }

}

?>
