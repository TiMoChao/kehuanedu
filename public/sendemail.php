<?php
sendemail();
function sendemail(){
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