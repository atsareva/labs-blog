<?php

class Helper
{
        public static function load_items($id = NULL)
    {
        $access = "access_id = 1";

        if (isset($_SESSION['user']) && !empty($_SESSION['user']))
        {
            $access_id = $_SESSION['user']['access_id'];

            switch ($access_id)
            {
                case 1:
                    $access = "access_id = 1";
                    break;
                case 2:
                    $access = "access_id = 1 OR access_id = 2";
                    break;
                case 4:
                    $access = "access_id = 1 OR access_id = 2 OR access_id = 4";
                    break;
                case 3:
                    $access = "access_id = 1 OR access_id = 2 OR access_id = 3  OR access_id = 4";
                    break;
            }
        }

        if ($id != NULL)
        {
            $query = "SELECT * FROM items_menu WHERE menu_id = '{$id}'AND (" . $access . ")";
        }
        else
        {
            $query  = "SELECT * FROM items_menu WHERE " . $access;
        }
        //$result = $this->select($where, 'items_menu');
        $result = self::$_db->sql($query);

        if (empty($result))
        {
            return $result;
        }
        if (!isset($result[0]) || !is_array($result[0]))
        {

            /**
             *  very very GAVNOKOD, but it works
             */
            $data      = $result;
            $result    = NULL;
            $result[0] = $data;
        }

        $result = self::menu_tree($result);
        return $result;
    }
    public static function menu_tree($menu, $parent_id = 0, $count = 0)
    {
        $menu_tree = array();
        $position  = 1;
        $child     = FALSE;
        $cycle     = 0;
        for ($cycle = 0; $cycle < count($menu); $cycle++)
        {
            if (($count < count($menu)) || ($cycle < count($menu)))
            {
                foreach ($menu as $item)
                {
                    if ($item['parent_id'] == $parent_id && $item['order_of'] == $position)
                    { {
                            $menu_tree[$item['id']]['id']     = $item['id'];
                            $menu_tree[$item['id']]['title']  = $item['title'];
                            $menu_tree[$item['id']]['path']   = $item['path'];
                            $menu_tree[$item['id']]['status'] = $item['status'];

                            $where  = array(
                                'id' => $item['access_id']
                            );
                            $access = $this->select($where, 'access');

                            $menu_tree[$item['id']]['access_id'] = $access['description'];

                            $menu_tree[$item['id']]['for_index'] = $item['for_index'];
                            $menu_tree[$item['id']]['dash']      = $item['dash'];
                            $count++;

                            $menu_tree = array_merge($menu_tree, $this->menu_tree($menu, $item['id'], $count));
                            $position  = $position + 1;
                            $cycle     = 0;
                        }
                    }
                }
            }
        }


        return $menu_tree;
    }
    static function front_menu()
    {
        $access = "access_id = 1";

        if (isset($_SESSION['user']) && !empty($_SESSION['user']))
        {
            $access_id = $_SESSION['user']['access_id'];

            switch ($access_id)
            {
                case 1:
                    $access = "access_id = 1";
                    break;
                case 2:
                    $access = "access_id = 1 OR access_id = 2";
                    break;
                case 4:
                    $access = "access_id = 1 OR access_id = 2 OR access_id = 4";
                    break;
                case 3:
                    $access = "access_id = 1 OR access_id = 2 OR access_id = 3  OR access_id = 4";
                    break;
            }
        }

        $query = "SELECT * FROM menu WHERE trash=0 And status = 1 AND (" . $access . ")";

        //$result = $this->select($where, 'items_menu');
        $menu = self::$_db->sql($query);

        if (!empty($menu))
        {
            if (isset($menu[0]) && is_array($menu[0]))
            {
                foreach ($menu as $value)
                {
                    $items_menu[$value['id']] = self::load_items($value['id']);
                    if ($value['show_title'] == 1)
                    {
                        $menu_name[$value['id']] = $value['title'];
                    }
                }
            }
            else
            {
                $items_menu[$menu['id']] = self::load_items($menu['id']);
                if ($menu['show_title'] == 1)
                {
                    $menu_name[$menu['id']] = $menu['title'];
                }
            }
            if (!isset($menu_name) || empty($menu_name))
            {
                $menu_name = FALSE;
            }
            return array($menu_name, $items_menu);
        }

        return FALSE;
    }
}
