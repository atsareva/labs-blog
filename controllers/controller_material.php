<?php

require_once CORE_PATH . 'controller/controller_a' . EXT;

class Controller_Material extends Controller_A
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

            if (empty($_POST['start_publication']))
                $data['start_publication'] = time();
            else
                $data['start_publication'] = strtotime($data['start_publication']);

            if (!empty($_POST['end_publication']))
                $data['end_publication'] = strtotime($data['end_publication']);

            $material = Core::getModel('material')->setData($data)->save();

            $saveExit = $_POST['save_exit'];
            unset($_POST);

            if ($saveExit)
                $this->redirect('admin/content');
            else
                $this->redirect('material/edit/' . $material->getId());
        }

        $head        = 'Создать материал';
        $materialUrl = Core::getBaseUrl() . 'material/create';

        $categories = Core::getModel('category')
                ->addFieldToFilter('trash', array('=' => 0))
                ->getCollection()
                ->getData();

        $this->_view->setTitle($head)
                ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuContent' => true))
                ->setChild('content', 'admin/material/form', array('head'        => $head, 'materialUrl' => $materialUrl, 'categories'  => $categories));
    }

    public function edit($id)
    {
        /**
         * @todo Validation
         */
        if (isset($id[0]))
        {
            $material = Core::getModel('material')->load((int) $id[0]);
            if ($material->getId())
            {
                if (isset($_POST) && !empty($_POST))
                {
                    $data = $_POST;
                    unset($data['save_exit'], $data['author']);

                    if (empty($data['author_id']))
                        $data['author_id'] = Core::getHelper('user')->getCurrentUser();

                    if (empty($_POST['start_publication']))
                        $data['start_publication'] = $material->getStartPublication();
                    else
                        $data['start_publication'] = strtotime($data['start_publication']);

                    if (empty($_POST['end_publication']))
                        $data['end_publication'] = $material->getEndPublication();
                    else
                        $data['end_publication'] = strtotime($data['end_publication']);

                    $data['modified'] = time();

                    $material->setData($data)->save();

                    $saveExit = $_POST['save_exit'];
                    unset($_POST);

                    if ($saveExit)
                        $this->redirect('admin/content');
                    else
                        $this->redirect('material/edit/' . $material->getId());
                }

                $head        = 'Редактировать материал';
                $materialUrl = Core::getBaseUrl() . 'material/edit/' . $material->getId();

                $categories = Core::getModel('category')
                        ->addFieldToFilter('trash', array('=' => 0))
                        ->getCollection()
                        ->getData();

                $author = Core::getModel('user')->load($material->getAuthorId());

                $this->_view->setTitle($head)
                        ->setChild('navBarMenu', 'admin/page/html/navbar-menu', array('menuContent' => true))
                        ->setChild('content', 'admin/material/form', array('head'        => $head, 'materialUrl' => $materialUrl, 'material'    => $material, 'categories'  => $categories, 'author'      => $author));
            }
            else
                $this->redirect('admin/content');
        }
        else
            $this->redirect('admin/content');
    }

    public function publicMaterial()
    {
        $ids  = explode(',', trim($_POST['ids'], ','));
        $bool = $_POST['bool'];

        foreach ($ids as $id)
            Core::getModel('material')->addFieldToFilter(array('id', 'status'))->load((int) $id)->setData('status', $bool)->save();
    }

    public function remove()
    {
        $ids = explode(',', trim($_POST['ids'], ','));
        foreach ($ids as $id)
            Core::getModel('material')->addFieldToFilter(array('id', 'trash'))->load((int) $id)->setTrash(1)->save();
    }

}

?>
