<?php

class NewsController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
//        $this->_helper->layout->setLayout("layout");
    }

    public function indexAction()
    {
        // action body
        $modelpage = new Kh_Model_Page();
        $where = array(
            'star' => 4,
            'top'  => 1
        );
        $newsStar = $modelpage->fetchRow($where);
        $this->view->starNews = $newsStar;

//        $where_list = array(
//            'star' => 4,
//            'top'  => 0
//        );
        $where_list = '1 = 1';
        $order = 'createtime DESC';
        $limit = 5;
        $newsList = $modelpage->getPages($where_list,$order,$limit);
        $this->view->newsList = $newsList;
    }


}

