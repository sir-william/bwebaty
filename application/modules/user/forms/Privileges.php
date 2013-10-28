<?php
class Admin_Form_Privileges extends Zend_Form
{
	public function init()
	{
		
		$this->addPrefixPath('Cms_Form_Element', 'Cms/Form/Element/', 'Element');
        
		$this->setOptions(array(
			'elements' => array(
				'role_id'=>array(
					'type'=>'text',
					'options'=>array(
						'label'=>'Selected role',
						'disabled'=>'disabled'
					)
				),
				'tree' => array(					
					'type' => 'treeView',
					'options' => array(
						'label' => 'Privileges',
						'multioptions' => array(
							'%,%,%' => array('title'=>'All','id'=>'all', 'children'=>array(
								"admin,%,%" => $this->getRessources(),
							)),
						),
					)
				),
				'submit' => array(
					'type' => 'submit',
					'options' => array(
						'ignore' => true,
						'label' => 'Submit',
						'class' => 'submit',
					)
				),
			),
		));

	}
	
	
	private function getRessources()
	{	
		$file = APPLICATION_PATH . '/admin/configs/about.xml';
		$reader = new XMLReader();
        $reader->open($file, 'UTF-8');
	        while ($reader->read()) {
            $str = $reader->nodeType . '_' . $reader->localName;
            switch ($str) {
                /* ========== Meet the open tag ============================= */
                case XMLReader::ELEMENT . '_ressource':
                		$moduleTitle = $reader->getAttribute('title');
                		$description = $reader->getAttribute('description');
                		$user = $reader->getAttribute('user');
                		$layout = $reader->getAttribute('layout');
                		$ressources=array('title'=>$moduleTitle,'id'=>$moduleTitle,'children'=>array());
                    	break;
                case XMLReader::ELEMENT . '_controller':
                		$controllerTitle = $reader->getAttribute('title');
                		$description = $reader->getAttribute('description');
                		$user = $reader->getAttribute('user');
                		$layout = $reader->getAttribute('layout');
                		$ressources['children'][$moduleTitle.','.$controllerTitle.',%'] = array('title'=>$controllerTitle,'id'=>$moduleTitle.$controllerTitle,'children'=>array());
                    	break;         
                case XMLReader::ELEMENT . '_action':
                		$actionTitle = $reader->getAttribute('title');
                		$description = $reader->getAttribute('description');
                		$user = $reader->getAttribute('user');
                		$layout = $reader->getAttribute('layout');
                		$ressources['children'][$moduleTitle.','.$controllerTitle.',%']['children'][$moduleTitle.','.$controllerTitle.','.$actionTitle] =array('title'=>$actionTitle,'id'=>$moduleTitle.$controllerTitle.$actionTitle, 'children'=>array());
                    break;
					/* ========== Meet the close tag ============================ */
                case XMLReader::END_ELEMENT . '_module':
                    break;
                case XMLReader::END_ELEMENT . '_controller':
                    break;
                case XMLReader::END_ELEMENT . '_action':
                    break;
            }
        }
        return $ressources;		
		
	}
}