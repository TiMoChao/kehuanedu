<?php

class CategoryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $modelCategory = new Kh_Model_Category();
        $order = "path ASC";
        $categroise = $modelCategory->getCategories(null,$order);
        $this->view->categorise = $categroise;
    }
}

