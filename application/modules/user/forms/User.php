<?php
class User_Form_User extends Zend_Form
{
    public function init() {
        $this->setMethod('post');
        $this->setAttrib('class','form');

        $id = $this->createElement('hidden', 'id');
        $id->setDecorators(array('ViewHelper'));
        $this->addElement($id);

        $username = $this->createElement('text','username');
        $username->setLabel('Username: ');
        $username->setRequired('true');
        $username->addFilter('StripTags');
        $this->addElement($username);

        $password = $this->createElement('password', 'password');
        $password->setLabel('Password: ');
        $password->setRequired('true');
        $this->addElement($password);

        $firstName = $this->createElement('text','first_name');
        $firstName->setLabel('First Name: ');
        $firstName->setRequired('true');
        $firstName->addFilter('StripTags');
        $this->addElement($firstName);

        $lastName = $this->createElement('text','last_name');
        $lastName->setLabel('Last Name: ');
        $lastName->setRequired('true');
        $lastName->addFilter('StripTags');
        $this->addElement($lastName);

        // create new element
        /*$roleId = $this->createElement('select', 'role_id');
        // element options
        $roleId->setLabel('Select a role: ');
        $roleId->setRequired(true);
        // populate this with the pages
        $mdlRole = new User_Model_Role();
        $roles = $mdlRole->fetchAll(null, 'name');
        if($roles->count() > 0) {
            foreach ($roles as $role) {
                $roleId->addMultiOption($role->id, $role->name);
            }
        }
        $this->addElement($roleId);*/

        $submit = $this->addElement('submit', 'submit', array('label' => 'Submit','class'=>'apply'));
    }
}
?>