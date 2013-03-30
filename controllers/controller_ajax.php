<?php

require_once 'model.php';

class Controller_Ajax extends Model
{

    public function load_users()
    {
        $query = "SELECT * FROM users";
        $result = $this->sql($query);

        if (isset($result[0]) && is_array($result[0]))
        {
            foreach ($result as $key => $value)
            {
                $data[] = array(
                    'id' => $result[$key]['id'],
                    'user_name' => $result[$key]['user_name'],
                    'email' => $result[$key]['email'],
                    'photo' => $result[$key]['photo']
                );
            }
        }
        else
        {
            $data[] = array(
                'id' => $result['id'],
                'user_name' => $result['user_name'],
                'email' => $result['email'],
                'photo' => $result['photo']
            );
        }

        $data = json_encode($data);
        echo $data;
    }

    public function load_attach()
    {
        $attach = $_POST['attach'];

        if ($attach == 'material')
        {
            $where = array(
                'status' => 1,
                'trash' => 0
            );
            $materials = $this->select($where, 'materials');
            if (!empty($materials) && is_array($materials))
            {
                if (isset($materials[0]) && is_array($materials[0]))
                {
                    foreach ($materials as $value)
                    {
                        $result[] = array(
                            'id' => $value['id'],
                            'alias' => $value['alias'],
                            'title' => $value['title']
                        );
                    }
                }
                else
                {
                    $result[] = array(
                        'id' => $materials['id'],
                        'alias' => $materials['alias'],
                        'title' => $materials['title']
                    );
                }
            }
        }
        else
        {
            $where = array(
                'status' => 1, 
                'trash' => 0
            );
            $categories = $this->select($where, 'categories');
            if (!empty($categories) && is_array($categories))
            {
                if (isset($categories[0]) && is_array($categories[0]))
                {
                    foreach ($categories as $value)
                    {
                        $result[] = array(
                            'id' => $value['id'],
                            'alias' => $value['alias'],
                            'title' => $value['title']
                        );
                    }
                }
                else
                {
                    $result[] = array(
                        'id' => $categories['id'],
                        'alias' => $categories['alias'],
                        'title' => $categories['title']
                    );
                }
            }
        }
        if (isset($result) && !empty($result))
        {
            $data = json_encode($result);
            echo $data;
        }
    }

}

?>
