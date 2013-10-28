<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initNamespace ()
    {
        Zend_Loader_Autoloader::getInstance()->registerNamespace('Baty_');
    }
    /**
     * plugin initialisation
     *
     */
    protected function _initPlugins ()
    {
        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new Baty_Controller_Plugin_Template());
    }
    protected function _initAutoload ()
    {
        // Add autoloader empty namespace
        $autoLoader     = Zend_Loader_Autoloader::getInstance();
        $resourceLoader = new Zend_Loader_Autoloader_Resource(
                                array('basePath' => APPLICATION_PATH,
                                      'namespace' => '',
                                      'resourceTypes' => array(
                                          'Baty' => array( 'path' => 'Baty/', 'namespace' => 'Baty_'),
                                          'form' => array('path' => 'modules/user/forms/', 'namespace' => 'User_Form_'),
                                      )
                                )
                            );
        // Return it so that it can be stored by the bootstrap
        //var_dump($resourceLoader);die;
        return $autoLoader;
    }
    protected function _initView ()
    {
        // Initialize view
        $view = new Zend_View();
        $view->setHelperPath(APPLICATION_PATH . '/views/helpers',
            'View_Helper');
        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer');
        $viewRenderer->setView($view);
        return $view;
    }
}

