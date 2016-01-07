<?php

class PageController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function detailAction()
    {
        // action body
        $id = $this->_request->getParam('id');
        $modelPage = new Kh_Model_Page();
        $page = $modelPage->getPage($id);
        $this->view->page = $page;

        //其它新闻文章列表
        $where = " id != ".$id;
        $pages =  $modelPage->getPages($where);
        $this->view->pages = $pages->toArray();
    }


}



