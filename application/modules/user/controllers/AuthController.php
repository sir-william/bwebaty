<?php
class User_AuthController extends Zend_Controller_Action
{
    public function indexAction ()
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->view->identity = $auth->getIdentity();
        }
    }
    public function loginAction ()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->layout()->setLayout('login');
        $userForm = new User_Form_User();
       // var_dump($userForm);die();
        $userForm->setAction('/user/auth/login');
        $userForm->removeElement('first_name');
        $userForm->removeElement('last_name');
        $userForm->removeElement('role_id');
        $this->view->form = $userForm;
        if ($this->_request->isPost() && $userForm->isValid($_POST)) {
            $data = $userForm->getValues();
            //set up the auth adapter
            // get the default db adapter
            $db = Zend_Db_Table::getDefaultAdapter();
            //create the auth adapter
            $authAdapter = new Zend_Auth_Adapter_DbTable($db, 
            'users', 'username', 'password');
            //set the username and password
            $authAdapter->setIdentity($data['username']);
            $authAdapter->setCredential(md5($data['password']));
            //authenticate
            $result = $authAdapter->authenticate();
            if ($result->isValid()) {
                // store the username, first and last names of the user
                $auth = Zend_Auth::getInstance();
                $storage = $auth->getStorage();
                $storage->write(
                $authAdapter->getResultRowObject(
                array('id', 'username', 'first_name', 'last_name', 'role_id')));
                $this->_helper->redirector('index', 'index');
            } else {
                $this->view->loginMessage = "Sorry, your username or password was incorrect";
            }
        }
        $this->view->form = $userForm;
    }
    public function logoutAction ()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->layout()->setLayout('login');
        $authAdapter = Zend_Auth::getInstance();
        $authAdapter->clearIdentity();
    }
}











