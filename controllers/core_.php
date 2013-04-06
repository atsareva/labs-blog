<?php

require_once 'db.php';
define('CLER', 'Controller_');

class Core extends Db
{

    private $_path = array(CPATH, VPATH, MPATH);

    static function auto_load()
    {
        if (isset($_SERVER['REQUESTED_URI']) && !empty($_SERVER['REQUESTED_URI']))
        {
            foreach ($_SERVER['REQUESTED_URI'] as $key => $value)
            {
                $get = explode('/', $key);
                break;
            }
            $param = array();
            foreach ($get as $key => $value)
            {
                switch ($key)
                {
                    case 0:
                        break;
                    case 1:
                        $class = strtolower($value);
                        break;
                    case 2:
                        $function = strtolower($value);
                        break;
                    default:
                        $param[] = $value;
                        break;
                }
            }

            $found = Core::find_file($class);

            if ($found)
            {
                ($param) ? $param : $param = NULL;
                (count($param) == 1) ? $param = $param[0] : $param;
                require $found;
                $class = CLER . ucwords(strtolower($class));
                $instance = new $class;
                if (isset($function) && !empty($function) && method_exists($instance, $function))
                {
                    $instance->$function($param);
                }
                else
                {
                    $function = 'index';
                    $instance->$function();
                }
            }
            else
            {
                require_once 'error.php';
            }
        }
        else
        {

            $class = strtolower(Config::$DEFULT_CONTROLLER);
            require_once strtolower(CLER . $class . EXT);
            $default_class = CLER . ucwords($class);
            $instance = new $default_class();
            $instance->index();
        }
    }

    public static function find_file($class)
    {
        $class = strtolower(CLER . $class . EXT);
        $path = CPATH . $class;
        if (is_file(CPATH . $class))
        {
            $found = CPATH . $class;
            return $found;
        }
        else
        {
            return FALSE;
        }
    }

    protected function view($name, $array)
    {
        include_once $name . EXT;
        return $array;
    }

    public static function redirect($uri = '', $method = 'location', $http_response_code = 302)
    {
        if (!preg_match('#^https?://#i', $uri))
        {
            //$uri = site_url($uri);
        }

        switch ($method)
        {
            case 'refresh' : header("Refresh:0;url=" . $uri);
                break;
            default : header("Location: " . $uri, TRUE, $http_response_code);
                break;
        }
        exit;
    }

    public function load_items($id = NULL)
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
            $query = "SELECT * FROM items_menu WHERE menu_id = '{$id}'AND (" . $access.")";
        }
        else
        {
            $query = "SELECT * FROM items_menu WHERE " . $access;
        }
        //$result = $this->select($where, 'items_menu');
        $result = $this->sql($query);

        if (empty($result))
        {
            return $result;
        }
        if (!isset($result[0]) || !is_array($result[0]))
        {

            /**
             *  very very GAVNOKOD, but it works
             */
            $data = $result;
            $result = NULL;
            $result[0] = $data;
        }
        
        $result = $this->menu_tree($result);
        return $result;
    }

    public function menu_tree($menu, $parent_id = 0, $count=0)
    {
        $menu_tree = array();
        $position = 1;
        $child = FALSE;
        $cycle = 0;
        for ($cycle = 0; $cycle < count($menu); $cycle++)
        {
            if (($count < count($menu)) || ($cycle < count($menu)))
            {
                foreach ($menu as $item)
                {
                    if ($item['parent_id'] == $parent_id && $item['order_of'] == $position)
                    { {
                            $menu_tree[$item['id']]['id'] = $item['id'];
                            $menu_tree[$item['id']]['title'] = $item['title'];
                            $menu_tree[$item['id']]['path'] = $item['path'];
                            $menu_tree[$item['id']]['status'] = $item['status'];

                            $where = array(
                                'id' => $item['access_id']
                            );
                            $access = $this->select($where, 'access');

                            $menu_tree[$item['id']]['access_id'] = $access['description'];

                            $menu_tree[$item['id']]['for_index'] = $item['for_index'];
                            $menu_tree[$item['id']]['dash'] = $item['dash'];
                            $count++;

                            $menu_tree = array_merge($menu_tree, $this->menu_tree($menu, $item['id'], $count));
                            $position = $position + 1;
                            $cycle = 0;
                        }
                    }
                }
            }
        }


        return $menu_tree;
    }

    function front_menu()
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

        $query = "SELECT * FROM menu WHERE trash=0 And status = 1 AND (" . $access.")";

        //$result = $this->select($where, 'items_menu');
        $menu = $this->sql($query);

        if (!empty($menu))
        {
            if (isset($menu[0]) && is_array($menu[0]))
            {
                foreach ($menu as $value)
                {
                    $items_menu[$value['id']] = $this->load_items($value['id']);
                    if ($value['show_title'] == 1)
                    {
                        $menu_name[$value['id']] = $value['title'];
                    }
                }
            }
            else
            {
                $items_menu[$menu['id']] = $this->load_items($menu['id']);
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

?>
