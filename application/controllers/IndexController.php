<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        if($this->getRequest()->isPost()){
            $subject = htmlspecialchars($_POST['subject']);
            $htmlbody = htmlspecialchars($_POST['htmlbody']);
            $sendfrom = htmlspecialchars($_POST['sendfrom']);
            $data = array(
               'subject' => $subject,
                'htmlbody' => $htmlbody,
                'sendfrom' => $sendfrom
            );
            $data = json_encode($data);
            $redis = new Redis();
            $redis->connect('redis.wochacha.com',6379);
            $redis->lPush('sendemail',$data);
        }
    }
}



