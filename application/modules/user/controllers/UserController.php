<?php
class User_UserController extends Zend_Controller_Action
{
    public function init ()
    {
        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->_response = $this->getResponse();
        $this->_response->insert('sidebar', 
        $this->view->render('user/user.options.phtml'));
    }
/*    public function changeKey ($res)
    {
        foreach ($res as $rowkey => $rowvalue) {
            foreach ($rowvalue as $key => $value) {
                if ($key == 0) {
                    $res[$rowkey]['module'] = $res[$rowkey][$key];
                    unset($res[$rowkey][$key]);
                }
                if ($key == 1) {
                    $res[$rowkey]['controller'] = $res[$rowkey][$key];
                    unset($res[$rowkey][$key]);
                }
                if ($key == 2) {
                    $res[$rowkey]['action'] = $res[$rowkey][$key];
                    unset($res[$rowkey][$key]);
                }
            }
        }
        return $res;
    }*/
    public function toArray ($array, $key, $separator)
    {
        $res = array();
        foreach ($array[$key] as $val)
            $res[] = explode($separator, $val);
        return $res;
    }
    public function All ($res)
    {
        if ($res['module'] == '%')
            return true;
    }
    public function allModule ($res)
    {
        if ($res['controller'] == '%')
            return true;
    }
    public function allController ($res)
    {
        if ($res['action'] == '%')
            return true;
    }
/*    public function eliminateDuplication ($res)
    {
        $rows = array();
        $module = null;
        $controller = null;
        $action = null;
        foreach ($res as $row) {
            foreach ($row as $key => $value) {
                if ($this->All($row)) {
                    $rows[] = $row;
                    return $rows;
                } elseif ($this->allModule($row)) {
                    if ($row['module'] == $module) {
                        break;
                    } else {
                        $rows[] = $row;
                        $module = $row['module'];
                    }
                } elseif ($this->allController($row)) {
                    if (($row['controller'] == $controller) or
                     ($row['module'] == $module)) {
                        break;
                    } else {
                        $rows[] = $row;
                        $controller = $row['controller'];
                    }
                } else {
                    if (($row['action'] == $action) or
                     ($row['controller'] == $controller) or
                     ($row['module'] == $module)) {
                        break;
                    } else {
                        $rows[] = $row;
                        $action = $row['action'];
                    }
                }
            }
        }
        return $rows;
    }*/
    public function createAction ()
    {
        $userForm = new User_Form_User();
        if ($this->_request->isPost()) {
            if ($userForm->isValid($_POST)) {
                $userModel = new Admin_Model_User();
                $userModel->createUser($userForm->getValue('username'), 
                $userForm->getValue('password'), 
                $userForm->getValue('first_name'), 
                $userForm->getValue('last_name'), $userForm->getValue('role'));
                return $this->_forward('list');
            }
        }
        $userForm->setAction('/user/user/create');
        $this->view->form = $userForm;
    }
    public function listAction ()
    {
        $currentUsers = Admin_Model_User::getUsers();
        if ($currentUsers->count() > 0) {
            $this->view->users = $currentUsers;
        } else {
            $this->view->users = null;
        }
    }
    public function updateAction ()
    {
        $userForm = new Admin_Form_User();
        $userForm->setAction('/user/user/update');
        $userForm->removeElement('password');
        $id = $this->_request->getParam('id');
        $userModel = new Admin_Model_User();
        $currentUser = $userModel->find($id)->current();
        $userForm->populate($currentUser->toArray());
        $this->view->form = $userForm;
    }
    public function deleteAction ()
    {
        $id = $this->_request->getParam('id');
        $userModel = new Admin_Model_User();
        $userModel->deleteUser($id);
        return $this->_forward('list');
    }
    public function rolesAction ()
    {
        $currentRoles = Admin_Model_Role::getRoles();
        if ($currentRoles->count() > 0) {
            $this->view->roles = $currentRoles;
        } else {
            $this->view->roles = null;
        }
    }
    public function updateRoleAction ()
    {
        $roleForm = new Admin_Form_Role();
        $roleForm->setAction('/user/user/update-role');
        $id = $this->_request->getParam('id');
        $roleModel = new Admin_Model_Role();
        $currentRole = $roleModel->find($id)->current();
        $roleForm->populate($currentRole->toArray());
        $this->view->form = $roleForm;
    }
    public function editRoleAction ()
    {
        $id = $this->_request->getParam('id');
        $roleModel = new Admin_Model_Role();
        $roleForm = new Admin_Form_Role();
        // if this is a postback, then process the form if valid
        if ($this->getRequest()->isPost()) {
            if ($roleForm->isValid($_POST)) {
                $role = $roleForm->getValue('name');
                $result = $roleModel->updateRole($id, $role);
                return $this->_forward('roles');
            }
        } else {
            // fetch the current role from the db
            $currentRole = $roleModel->find($id)->current();
            // populate the form
            $roleForm->populate($currentRole->toArray());
        }
        $roleForm->setAction('/admin/user/edit-role');
        // pass the form to the view to render
        $this->view->form = $roleForm;
    }
    public function createRoleAction ()
    {
        $roleForm = new Admin_Form_Role();
        if ($this->_request->isPost()) {
            if ($roleForm->isValid($_POST)) {
                $roleModel = new Admin_Model_Role();
                $roleModel->createRole($roleForm->getValue('role'));
                return $this->_forward('roles');
            }
        }
        $roleForm->setAction('/admin/user/create-role');
        $this->view->form = $roleForm;
    }
    public function privilegesAction ()
    {
        $res = array();
        $id = $this->_request->getParam('id');
        $privilegeModel = new Admin_Model_Privilege();
        $roleModel = new Admin_Model_Role();
        $form = new Admin_Form_Privileges();
        $mdlPrivilege = new Admin_Model_Privilege();
        if ($this->_request->isPost()) {
            if ($form->isValid($_POST)) {
                $values = $form->getValues(true);
                $res = $this->toArray($values, 'tree', ',');
                $res = $this->changeKey($res);
                $res = $this->eliminateDuplication($res);
                $mdlPrivilege->updatePrivileges($id, $res);
                $this->_forward('roles');
            }
        } else {
            // fetch the current role from the db
            $privileges = $privilegeModel->find($id)->current();
            $role=$roleModel->find($id)->current();
            // populate the form
            $this->view->currentPrivileges=$privilegeModel->getPrivileges($id);
            $form->role_id->setValue($role->name);
            
        }
        //$form->setAction('/admin/user/privileges');
        $this->view->form = $form;
    }
}











