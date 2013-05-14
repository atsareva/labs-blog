<?php

class Controller_Ajax
{

    /**
     * Retrieve department by faculty id
     */
    public function loadDepartment()
    {
        $response = array('data' => false);
        if (isset($_POST['faculty_id']) && $_POST['faculty_id'] != 0)
        {
            $departments = Core::getModel('department')
                    ->addFieldToFilter('faculty_id', array('=' => $_POST['faculty_id']))
                    ->getCollection()
                    ->getData();

            if (!isset($departments[0]->id))
                $departments = Core::getModel('department')
                        ->getCollection()
                        ->getData();

            foreach ($departments as $department)
                $response['data'][$department->id] = $department->name;
        }

        echo json_encode($response);
    }

    /**
     * Block User by id
     */
    public function blockUser()
    {
        $id = $_POST['id'];

        $user = Core::getModel('user')->load((int) $id);

        if ($user->getId())
        {
            if ($user->getBlock() == 0)
                $user->setBlock(1);
            else
                $user->setBlock(0);
            $user->save();

            echo json_encode(array('id'    => $user->getId(), 'block' => $user->getBlock()));
        }
        else
            echo json_encode(array('id' => false));
    }

    /**
     * Set for Index by ID
     */
    public function forIndex()
    {
        $id = $_POST['id'];

        $menuItem = Core::getModel('menu_items')->load((int) $id);

        if ($menuItem->getId())
        {
            if ($menuItem->getForIndex() == 0)
                $menuItem->setForIndex(1);
            else
                $menuItem->setForIndex(0);
            $menuItem->save();

            echo json_encode(array('id'        => $menuItem->getId(), 'for_index' => $menuItem->getForIndex()));
        }
        else
            echo json_encode(array('id' => false));
    }

    /**
     * Retrieve material for attachment to menu item
     */
    public function loadAttachment()
    {
        $attach = $_POST['attach'];

        if ($attach === 'material')
        {
            $materials = Core::getModel('material')->getCollection()->getData();
            if (count($materials) > 0)
                foreach ($materials as $material)
                    $data[]    = array(
                        'id'    => $material->id,
                        'alias' => $material->alias,
                        'title' => $material->title
                    );
        }
        else
        {
            $categories = Core::getModel('category')->getCollection()->getData();
            if (count($categories) > 0)
                foreach ($categories as $category)
                    $data[]     = array(
                        'id'    => $category->id,
                        'alias' => $category->alias,
                        'title' => $category->title
                    );
        }
        if (isset($data))
            echo json_encode($data);
    }

    /**
     * Favorite material by id
     */
    public function favoriteMaterial()
    {
        $id = $_POST['id'];

        $material = Core::getModel('material')->addFieldToFilter(array('id', 'favorite'))->load((int) $id);

        if ($material->getId())
        {
            if ($material->getFavorite() == 0)
                $material->setFavorite(1);
            else
                $material->setFavorite(0);
            $material->save();

            echo json_encode(array('id'       => $material->getId(), 'favorite' => $material->getFavorite()));
        }
        else
            echo json_encode(array('id' => false));
    }

    public function loadUsers()
    {
        $users = Core::getModel('user')->getCollection()->getData();

        foreach ($users as $user)
            $data[] = array(
                'id'        => $user->id,
                'user_name' => $user->user_name,
                'email'     => $user->email,
                'photo'     => Core::getBaseUrl() . $user->photo
            );

        echo json_encode($data);
    }

    public function load_attach()
    {
        $attach = $_POST['attach'];

        if ($attach == 'material')
        {
            $where     = array(
                'status' => 1,
                'trash'  => 0
            );
            $materials = $this->select($where, 'materials');
            if (!empty($materials) && is_array($materials))
            {
                if (isset($materials[0]) && is_array($materials[0]))
                {
                    foreach ($materials as $value)
                    {
                        $result[] = array(
                            'id'    => $value['id'],
                            'alias' => $value['alias'],
                            'title' => $value['title']
                        );
                    }
                }
                else
                {
                    $result[] = array(
                        'id'    => $materials['id'],
                        'alias' => $materials['alias'],
                        'title' => $materials['title']
                    );
                }
            }
        }
        else
        {
            $where      = array(
                'status' => 1,
                'trash'  => 0
            );
            $categories = $this->select($where, 'categories');
            if (!empty($categories) && is_array($categories))
            {
                if (isset($categories[0]) && is_array($categories[0]))
                {
                    foreach ($categories as $value)
                    {
                        $result[] = array(
                            'id'    => $value['id'],
                            'alias' => $value['alias'],
                            'title' => $value['title']
                        );
                    }
                }
                else
                {
                    $result[] = array(
                        'id'    => $categories['id'],
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
