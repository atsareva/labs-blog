<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Category extends Controller_A
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
            unset($data['save_exit'], $data['author']);

            if (empty($data['author_id']))
                $data['author_id'] = Core::getHelper('user')->getCurrentUser();

            $data['created'] = time();

            $category = Core::getModel('category')->setData($data)->save();

            $saveExit = $_POST['save_exit'];
            unset($_POST);

            if ($saveExit)
                $this->redirect('admin/category');
            else
                $this->redirect('category/edit/' . $category->getId());
        }

        $head        = 'Создать категорию';
        $categoryUrl = Core::getBaseUrl() . 'category/create';

        $this->_view->setTitle($head)
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuCategory' => true))
                ->setChild('content', 'admin/category/form', array('head'        => $head, 'categoryUrl' => $categoryUrl));
    }

    public function edit($id)
    {
        /**
         * @todo Validation
         */
        if (isset($id[0]))
        {
            $category = Core::getModel('category')->load((int) $id[0]);
            if ($category->getId())
            {
                if (isset($_POST) && !empty($_POST))
                {
                    $data = $_POST;
                    unset($data['save_exit'], $data['author']);

                    if (empty($data['author_id']))
                        $data['author_id'] = Core::getHelper('user')->getCurrentUser();

                    $data['modified'] = time();

                    $category->setData($data)->save();

                    $saveExit = $_POST['save_exit'];
                    unset($_POST);

                    if ($saveExit)
                        $this->redirect('admin/category');
                    else
                        $this->redirect('category/edit/' . $category->getId());
                }

                $head        = 'Редактировать категорию';
                $categoryUrl = Core::getBaseUrl() . 'category/edit/' . $category->getId();

                $author = Core::getModel('user')->load($category->getAuthorId());

                $this->_view->setTitle($head)
                        ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuCategory' => true))
                        ->setChild('content', 'admin/category/form', array('head'        => $head, 'categoryUrl' => $categoryUrl, 'category'    => $category, 'author'      => $author));
            }
            else
                $this->redirect('admin/category');
        }
        else
            $this->redirect('admin/category');
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

}

?>
