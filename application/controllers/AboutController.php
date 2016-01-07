<?php

class AboutController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $medelPage = new Kh_Model_Page();
        $where = array('cid' => 2);
        $aboutPages = $medelPage->getPages($where);
        $this->view->aboutPages = $aboutPages;
    }
}

