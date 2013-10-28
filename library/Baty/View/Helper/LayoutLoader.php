<?php
/**
 *
 * @author Salahedine MOUDNI <moudni.salaheddine@gmail.com>
 *
 */
class Baty_View_Helper_LayoutLoader extends Zend_View_Helper_Abstract
{
    const CONTAINER_ID_PREFIX = 'block_';
    const CONTAINER_CLASS = 'widget';
    const WIDGET_CLASS = 'widget';
    /**
     * Use 960 grid framework for layout with total of 12 columns
     */
    const TOTAL_COLUMNS = 11;
    private static $_posToClass = array('first' => 'alpha', 'last' => 'omega');
    /**
     * Widget resources
     * @var array
     */
    private $_resources = array('javascript' => array(), 'css' => array());

    public function layoutLoader ()
    {
        $frontController = Zend_Controller_Front::getInstance();
        $request = $frontController->getRequest();
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $routeName = $module . '_' . $controller . '_' . $action;
        $routeName = strtolower($routeName);
        $file = Zend_Layout::getMvcInstance()->getLayoutPath() . '/' . $routeName .  '.xml';
        if (! file_exists($file)) {
            /**
             * If the layout file does not exist, just show output of route action as normal
             */
            return $this->view->layout()->content;
        }
        /**
         * Process layout file
         */
        ob_start();
        $reader = new XMLReader();
        $reader->open($file, 'UTF-8');
        $module = null;
        $widgetName = null;
        $load = null;
        $containerId = 0;
        $widgetContainerId = 0;
        $tabId = 0;
        $tabContainerId = 0;
        while ($reader->read()) {
            $str = $reader->nodeType . '_' . $reader->localName;
			
            switch ($str) {
                /* ========== Meet the open tag ============================= */
                case XMLReader::ELEMENT . '_layout':
                    break;
                case XMLReader::ELEMENT . '_block':
                    $containerId ++;
                    $widgetContainerId = 0;
                    /*$cols = $reader->getAttribute('cols');
                    if ($cols == null) {
                        $class = '';
                    } else {
                        $class = (($position = $reader->getAttribute('position')) != null) ? 'grid_' . $cols . ' ' .self::$_posToClass[$position] : 'grid_' . $cols;
                    }
                    if ($cols == self::TOTAL_COLUMNS) {
                        $class .= ' t_space_bottom';
                    }
					
                    $cssClass = $reader->getAttribute('cssClass');
                    if (isset($cssClass)) {
                        $class .= ' ' . $cssClass;
                    }
                    $str = '<div class="' . self::CONTAINER_CLASS . ' ' . $class . '" id="' . self::CONTAINER_ID_PREFIX . $containerId . '">';*/
                    $str = '<div class="' . self::CONTAINER_CLASS .'" id="' . self::CONTAINER_ID_PREFIX . $containerId . '">';
                    echo $str;
                    break;
				case XMLReader::ELEMENT . '_defaultOutput':
					/**
					 * Render the script normally
					 */
					$str=$this->view->layout()->content;
					echo $str;
					break;
				case XMLReader::ELEMENT . '_template':
					$moduleName = $reader->getAttribute('module');
					$controllerName = $reader->getAttribute('controller');
					$actionName = $reader->getAttribute('action');
					$param = $reader->getAttribute('param');
					$value = $reader->getAttribute('value');
					$template = $reader->getAttribute('script');
				    $layoutpath = Zend_Layout::getMvcInstance()->getViewScriptPath();
                    $this->view->addScriptPath($layoutpath.'/../templates/'.$moduleName. '/'.$controllerName . '/') ;
                    $str = '<div class="partial">'.$this->view->render($template).'</div>';
                    echo $str;
					break;
				case XMLReader::ELEMENT . '_htmlblock':
					$str = '<div class="htmlpartial">'.$reader->readString().'</div>';
                    echo $str;           
					/* ========== Meet the close tag ============================ */
                case XMLReader::END_ELEMENT . '_layout':
                    break;
                case XMLReader::END_ELEMENT . '_block':
                    echo '</div>';
                    break;
                case XMLReader::END_ELEMENT . '_defaultOutput':
                    break;
                case XMLReader::END_ELEMENT . '_template':
                    break;
				case XMLReader::END_ELEMENT . '_htmlblock':
					break;
            }
        }
    }
}