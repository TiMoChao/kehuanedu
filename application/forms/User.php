<?php

class Kh_Form_User extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->setMethod('post');

        //用户名
        $userName = $this->createElement('text','username');
        $userName->setLabel('用户');
        $userName->setRequired(TRUE);
        $userName->addValidator('stringLength',false,array(5,20));
        $userName->addErrorMessage('中户名要求英文5-20个字母或者2-6个汉字');
        $this->addElement($userName);

        //性别
        $sex = $this->createElement('radio','sex');
        $sex->setLabel('性别');
        $sex->addMultiOptions(array(1=>'男',0=>'女'));
        $sex->addFilter('StringToLower');
//        $userName->setRequired(TRUE);
        $sex->setSeparator("");
        $this->addElement($sex);

        //密码
        $password = $this->createElement('password','password');
        $password->setLabel('密码');
        $password->setRequired(TRUE);
        $password->addValidator('stringLength',false,array(6));
        $password->addErrorMessage('密码要求至少6个字符');
        $this->addElement($password);

        //确认密码
        $password2 = $this->createElement('password','password2');
        $password2->setLabel('确认密码');
        $password2->setRequired(TRUE);
        $password2->addValidator('identical',false,array('token'=>'password'));
        $password2->addErrorMessage('两次输入的密码不同');
        $this->addElement($password2);

        //电子邮件
        $email = $this->createElement('text','email');
        $email->setLabel('电子邮箱');
        $email->setRequired(TRUE);
        $email->addValidator('EmailAddress');
        $email->addErrorMessage('请输入一个有效的邮箱地址');
        $this->addElement($email);

        //个人简介
        $profile = $this->createElement('textarea','profile');
        $profile->setLabel('个人简介');
        $profile->setAttribs(array('rows'=>4,'cols'=>50));
        $this->addElement($profile);

        //用户头像
        $avatar = $this->createElement('file','avatar');
        $avatar->setLabel('用户头像');
        $avatar->setRequired(FALSE);
        $this->addElement($avatar);

        //用户状态
        $status = $this->createElement('select','status');
        $status->setLabel('用户状态');
        $status->addMultiOptions(array('0'=>'锁定','1'=>'激活'));
        $status->setRequired(TRUE);
        $this->addElement($status);

        //用户角色
        $role = $this->createElement('select','role');
        $role->setLabel('用户状态');
        $role->addMultiOptions(array(
            'user'=>'用户',
            'student'=>'学生',
            'teacher'=>'教师',
            'author'=>'作者',
            'editor'=>'编辑',
            'admin'=>'管理员',

        ));
        $role->setRequired(TRUE);
        $this->addElement($role);

        //提交按钮
        $submit = $this->createElement('submit','提交');
        $this->addElement($submit);
    }

}

