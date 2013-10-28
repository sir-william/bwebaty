<?php
/**
 *
 * @author Salahedine MOUDNI <moudni.salaheddine@gmail.com>
 *
 */
class Baty_Controller_Plugin_Template extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $request         = $frontController->getRequest();
        $controller      = $request->getControllerName();
        $action          = $request->getActionName();
        $module          = $request->getModuleName();

        $templateback = 'batydesignback';
        $templatefront = 'defaultback';
        $view = Zend_Layout::startMvc()->getView();
        if ($this->getTypeOfressource($module,$controller,$action)== 'back'){
            $layout = APPLICATION_PATH.'/design/'.$templateback.'/layouts'; //dynamic after work
            $view->setBasePath(APPLICATION_PATH.'/design/'.$templateback.'/templates/');
            $view->setScriptPath( APPLICATION_PATH.'/design/'.$templatefront.'/templates/default');

        }else{
            $layout = APPLICATION_PATH.'/design/'.$templatefront.'/layouts'; //dynamic after work
           // $view->setBasePath(APPLICATION_PATH.'/design/'.$templatefront.'/templates/');
            $view->setScriptPath( APPLICATION_PATH.'/design/'.$templatefront.'/templates/default');
        }

        $view->addHelperPath('Baty/View/Helper', 'Baty_View_Helper');
        Zend_Layout::getMvcInstance()->setLayoutPath($layout);

    }
    private  function getTypeOfressource($module,$controller,$action){
        $design = new Zend_Config_Xml(APPLICATION_PATH."/modules/".$module."/config.xml");
        //var_dump($design->design->controllers->$controller->$action->template);die();
        if(null != $design->design->controllers->$controller->$action->template)
            return  $design->design->controllers->$controller->$action->template;

    }

}