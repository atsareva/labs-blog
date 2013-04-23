<?php

require_once CORE_PATH . 'controller/controller' . EXT;

Class Controller_Home extends Controller
{

    function index()
    {
        $data     = array();
        $viewPath = 'front/materials/content';
        $title    = '';

        if (isset($_GET['id']) && (int) $_GET['id'] > 0)
        {
            //getting material
            if (isset($_GET['page']) && $_GET['page'] === 'material')
            {
                $data     = $this->_getMaterial();
                if (!empty($data))
                    $viewPath = 'front/materials/material';
            }
            //getting category
            if (isset($_GET['page']) && $_GET['page'] === 'category')
            {
                $data     = $this->_getCategory();
                if (!empty($data))
                    $viewPath = 'front/materials/category';
            }
        }
        else
        {
            $menuItems = Core::getModel('menu_items')
                    ->addFieldToFilter('path')
                    ->addFieldToFilter('status', array('=' => 1))
                    ->addFieldToFilter('trash', array('=' => 0))
                    ->addFieldToFilter('for_index', array('=' => 1))
                    ->getCollection()
                    ->getData();
            if (isset($menuItems[0]->path))
                $this->redirect($menuItems[0]->path);
            else
                $this->redirect('home/front');
        }

        $this->_view->setChild('content', $viewPath, $data);
    }

    public function front()
    {
        $materialList = Core::getModel('material')->getCollection();
        $this->_view->setTitle('Главная')
                ->setChild('content', 'front/materials/content', array('materialList' => $materialList));
    }

    private function _getMaterial()
    {
        $material = Core::getModel('material')->load((int) $_GET['id']);
        if ($material->getId())
        {
            $category = Core::getModel('category')
                    ->addFieldToFilter('title')
                    ->load($material->getCategoryId());
            $user     = Core::getModel('user')->load($material->getAuthorId());

            $this->_view->setTitle($material->getTitle());
            return array(
                'category' => $category,
                'material' => $material,
                'user'     => $user);
        }
        return array();
    }

    private function _getCategory()
    {
        $category = Core::getModel('category')
                ->addFieldToFilter('status', array('=' => 1))
                ->addFieldToFilter('trash', array('=' => 0))
                ->load((int) $_GET['id']);
        if ($category->getId())
        {
            $user         = Core::getModel('user')->load($category->getAuthorId());
            $materialList = Core::getModel('material')
                    ->addFieldToFilter(array('id', 'title'))
                    ->addFieldToFilter('category_id', array('=' => (int) $_GET['id']))
                    ->addFieldToFilter('status', array('=' => 1))
                    ->addFieldToFilter('trash', array('=' => 0))
                    ->addFieldToFilter('start_publication', array('<=' => time()))
                    ->addFieldToFilter('end_publication', array(array('>' => time()), array('=' => 0)))
                    ->getCollection();

            $this->_view->setTitle($category->getTitle());
            return array(
                'category'     => $category,
                'materialList' => $materialList,
                'user'         => $user);
        }
        return array();
    }

}

?>
