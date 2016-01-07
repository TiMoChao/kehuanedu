<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    }

    /**
     * @throws Zend_Form_Exception
     * 用户注册
     */
    public function registerAction()
    {
        $formRegister = new Kh_Form_User();
        $formRegister->removeElement('avatar');
        $formRegister->removeElement('status');
        $formRegister->removeElement('role');
        $formRegister->removeElement('profile');
        if($this->getRequest()->isPost()){
            if($formRegister->isvalid($_POST)){
                $userData = $formRegister->getValues();
                $modelUser = new Kh_Model_User();

                $validUser = $modelUser->validUser($formRegister->getValue('username'));
                if($validUser == FALSE){
                    $newUser = $modelUser->createUser($userData);
                    if($newUser){

                        //发送注册成功的邮件，到时候提出来写成方法service
//                    set_time_limit(0);
                        $mailTransport = new Zend_Mail_Transport_Smtp('smtp.126.com',
                            array(
                                'auth' => 'login',
                                'username' => 'luochao252@126.com',
                                'password' => 'lcaimama123',
//                        'ssl' => 'ssl',//安全模式会失败
                            )
                        );
                        $mail = new Zend_Mail('utf-8');
                        $mail->setBodyHtml('<p>'.$formRegister->getValue('username').',欢迎你成为我们的用户！.</p>');
                        $mail->setSubject('欢迎成为可幻教育网的用户');
                        $mail->setFrom('luochao252@126.com','罗超');
                        $mail->addTo($formRegister->getValue('email'),$formRegister->getValue('username'));
                        $mail->send($mailTransport);
                        $this->_redirect('/user/account/id/'.$newUser);
                    }
                    else{
                        throw new Zend_Exception("添加用户出错");
                    }
                }
                else{
                    $this->view->message = "对不起，已经存在相同的用户，请更换一个用户名试试";
                }

            }
        }
        $this->view->formRegister = $formRegister;
    }

    public function accountAction()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $this->view->identity = $auth->getIdentity();
        }
        $id = $this->_request->getParam('id');
        $modelUser = new Kh_Model_User();
        $user = $modelUser->getUser($id);
        $this->view->user = $user;
    }

    public function loginAction()
    {
        $formLogin = new Kh_Form_User();
        $formLogin->removeElement('sex');
        $formLogin->removeElement('email');
        $formLogin->removeElement('avatar');
        $formLogin->removeElement('password2');
        $formLogin->removeElement('status');
        $formLogin->removeElement('profile');
        $formLogin->removeElement('role');

        if($this->getRequest()->isPost()){
            if($formLogin->isValid($_POST)){
                $data = $formLogin->getValues();

                //取得默认的数据库适配器
                $db = Zend_Db_Table::getDefaultAdapter();
                //实例化一个Auth适配器
                $authAdapter = new Zend_Auth_Adapter_DbTable($db,'core_users','username','password');
                //设置认证用户名和密码
                $authAdapter->setIdentity($data['username']);
                $authAdapter->setCredential(md5($data['password']));
                //设置authenticate方法
                $result = $authAdapter->authenticate();
                if($result->isValid()){
                    //获得getInstance实例
                    $auth = Zend_Auth::getInstance();
                    $storage = $auth->getStorage();
                    $storage->write($authAdapter->getResultRowObject(
                       array('id','username','role')
                    ));
                    $id = $auth->getIdentity()->id;//获取用户id
                    //记录登录时间
                    $modelUser = new Kh_Model_User();
                    $loginTime = $modelUser->loginTime($id);

                    return $this->_redirect('/user/account/id/'.$id);
                }
            }
        }
        $this->view->formUser = $formLogin;
    }

    /**
     * 用户面板
     *
     * 获取身份
     */
    public function panelAction()
    {
        //用户面板
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()){
            $this->view->identity = $auth->getIdentity();
        }
    }

    /**
     * 退出登录
     */
    public function logoutAction()
    {
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
        return $this->_redirect('/index/index');
    }

    /**
     * 用户信息修改
     *
     * 用户登陆后可自行修改信息
     * 包括上传头像
     */
    public function updateAction()
    {
        $id = $this->_request->getParam('id');
        $formUser = new Kh_Form_User();
        $formUser->removeElement('username');
        $formUser->removeElement('password');
        $formUser->removeElement('password2');
        $formUser->removeElement('role');
        $formUser->removeElement('status');
        $modelUser = new Kh_Model_User();

        //修改信息
        if($this->getRequest()->isPost()){
            if($formUser->isValid($_POST)){
                //上传图片和获取图片信息
                $adapter = new Zend_File_Transfer_Adapter_Http();
                $path = APPLICATION_PATH.'/../public/uploads/'.date('Y-m').'/avatar/';
                $folder = new Zend_Search_Lucene_Storage_Directory_Filesystem($path);//如果文件夹不存在则创建文件夹
                $fileInfo = $adapter->getFileInfo();//获取基本文件信息
                $extName = $this->_getExtension($fileInfo);//获取扩展名
                $filename = time().'.'.$extName;//文件重命名
                $adapter->addFilter('Rename',array('target' => $filename,
                    'overwrite' => true
                    ));//执行重命名
                $adapter->setDestination($path);//设定保存路径
                $adapter->addValidator('Size',FALSE,128000);//验证文件大小
                $adapter->addValidator('Extension',FALSE,array('jpg','git','png','jpeg'));//验证文件扩展名

                //获取其他表单的数据
                $data = array();
                $data['sex'] = $formUser->getValue('sex');
                $data['email'] = $formUser->getValue('email');
                $data['profile'] = $formUser->getValue('profile');

                if($adapter->receive()){//如果执行上传
                    $data['avatar'] = $filename;
                    $updataUser = $modelUser->updateUser($id,$data);

                }
                else{
                    if($formUser->getValue('avatar')){
                        throw new Zend_Exception('图片太大或者图片格式不对，未能上传成功');
                        exit;
                    }
                    else{
                        $updataUser = $modelUser->updateUser($id,$data);
                    }
                }
                if($updataUser){
                    $this->_redirect('/user/account/id/'.$id.'/'.$updataUser);
                }
                else{
                    throw new Zend_Exception('用户更新出错');
                }
            }
        }
        $user = $modelUser->find($id)->current();
        $formUser->populate($user->toArray());//该方法可以将数据填充到表单中去，里面的参数是数组，所以用到to_array()方法将对象转换成数组
        $this->view->user = $user;
        $this->view->formUser = $formUser;
    }

    /**
     * @throws Zend_Exception
     * @throws Zend_Form_Exception
     * 修改密码
     */
    public function changePasswordAction()
    {
        $id = $this->_request->getParam('id');
        $formUser = new Kh_Form_User();
        $formUser->removeElement('username');
        $formUser->removeElement('sex');
        $formUser->removeElement('email');
        $formUser->removeElement('avatar');
        $formUser->removeElement('profile');
        $formUser->removeElement('role');
        $formUser->removeElement('status');
        if($this->getRequest()->isPost()){
            if($formUser->isValid($_POST)){
                $modelUser = new Kh_Model_User();
                $newpsw = $modelUser->changePassword($id,$formUser->getValue('password'));
                return $this->_forward('account');
            }
        }
        $this->view->formUser = $formUser;
    }

    /**
     * 重置密码
     *
     * 将用户的密码更新为一个随机的字符串
     * 将该密码发送到用户的注册邮箱
     */
    public function resetPasswordAction()
    {
        $formUser = new Kh_Form_User();
        $formUser->removeElement('sex');
        $formUser->removeElement('email');
        $formUser->removeElement('avatar');
        $formUser->removeElement('profile');
        $formUser->removeElement('role');
        $formUser->removeElement('status');
        $formUser->removeElement('password');
        $formUser->removeElement('password2');

        if($this->getRequest()->isPost()){
            if($formUser->isValid($_POST)){
                $modelUser = new Kh_Model_User();
                $where = array('username' => $formUser->getValue('username'));
                $user = $modelUser->getUser($where);
                if($user){
                    set_time_limit(0);//不限制时间，默认时间为30秒
                    $newPassword = $this->_makePassword(6);
                    $modelUser->updateUser($user->id,null,$newPassword);

                    $mailTransport = new Zend_Mail_Transport_Smtp('smtp.126.com',
                        array(
                            'auth' => 'login',
                            'username' => 'luochao252@126.com',
                            'password' => 'lcaimama123',
//                        'ssl' => 'ssl',//安全模式会失败
                        )
                    );
                    $mail = new Zend_Mail('utf-8');
                    $mail->setBodyHtml('<p>您的密码被系统重置为:'.$newPassword.'<b />建议您在下次登录后重新更改为容易记忆的新密码。</p>');
                    $mail->setSubject('您在可幻教育的新密码');
                    $mail->setFrom('luochao252@126.com','罗超');
                    $mail->addTo($user->email,$user->username);
                    $mail->send($mailTransport);

                    $this->_redirect('/user/info/conf/resetpsw_success');
                }
                else{

                }

            }
        }
        $this->view->formUser = $formUser;
    }

    /**
     * @param $fileInfo
     * @return array
     * 获取上传文件的文件扩展名
     */
    protected function _getExtension($fileInfo)
    {
        $fileName = '';
        if($fileInfo){
            foreach ($fileInfo as $value) {
                $fileName = $value['name'];
            }
            $exts = split("[/.]",$fileName);
            $n = count($exts)-1;
            $exts = $exts[$n];
            return $exts;
        }
    }

    /**
     * @param $length
     * @return string
     * 随机生成随机字符串
     */
    protected function _makePassword($length)
    {
        $possible = "0123456789!@#$%^&*()_+qwertyuiopasdfghjklzxcvbnmZXCVBNMLKJHGFDSAQWERTYUIOP";
        $str = "";
        while(strlen($str) < $length){
            $str .= substr($possible,(rand() % strlen($possible)),1);
        }
        return $str;
    }

    /**
     * 确认信息
     */
    public function infoAction()
    {
        $conf = $this->_request->getParam('conf');
        if($conf == 'resetpsw_success'){
            echo '密码重置成功，请到您的注册邮箱中查收新密码。';
        }
    }
}

















