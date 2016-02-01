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

        $where_list = '1 = 1';
        $order = 'createtime DESC';
        $limit = 5;
        $newsList = $modelpage->getPages($where_list,$order,$limit);
//         $this->sendemail();
        $this->view->newsList = $newsList;
    }

  public function sendemail(){
        $redis = new Redis();
        $redis->connect('127.0.0.1',6379);
        $mailTransport = new Zend_Mail_Transport_Smtp('smtp.126.com',
            array(
                'auth' => 'login',
                'username' => 'luochao252@126.com',
                'password' => 'lcaimama123',
//                        'ssl' => 'ssl',//安全模式会失败
            )
        );
        while(True){
            try{
                $task =  $redis->rPop('sendemail');
                if($task){
                    $data = json_decode($task);
                    //开始发送邮件
                    set_time_limit(0);//不限制时间，默认时间为30秒
                    $mail = new Zend_Mail('utf-8');
                    $mail->setBodyHtml($data->htmlbody);
                    $mail->setSubject($data->subject);
                    $mail->setFrom('luochao252@126.com','罗超');
                    $mail->addTo($data->sendfrom,'luochao');
                    $mail->send($mailTransport);
                }
            }catch(Exception $e){
                echo $e->getMessage()."\n";
            }
            sleep(rand()%3);
        }
    }

}

