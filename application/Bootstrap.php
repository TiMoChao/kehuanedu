<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initView(){
        ini_set('date.timezone','Asia/Shanghai');//设置时区

        $view = new Zend_View();//实例化两个资源
        $view->doctype('XHTML1_STRICT');
        $view->headTitle('可幻教育');

        return $view;
    }

    protected  function _initNavigation(){
        $this->bootstrap('layout');
        $layout = $this->getResource('layout');
        $view = $layout->getView();
        $config = new Zend_Config_Xml(APPLICATION_PATH.'/configs/navigation.xml');
        $navigation = new Zend_Navigation($config);
        $view->navigation($navigation);
    }
}

