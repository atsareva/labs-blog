<?php

require_once 'model.php';

Class Controller_Favourite extends Model
{

   public function __construct()
    {
        parent::__construct();
        if ((!isset($_SESSION['user']) || empty($_SESSION['user'])) || $_SESSION['user']['access_id'] < 3)
        {
            header('Location: http://' . $this->BASE_URL . '/auth/login');
        }
    }

    public function load_material()
    {
        $where = array(
            'trash' => 0,
            'favorite' => 1
        );
        $result = $this->select($where, 'materials');
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
