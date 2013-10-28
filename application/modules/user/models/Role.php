<?php
require_once 'Zend/Db/Table/Abstract.php';
require_once APPLICATION_PATH . '/admin/models/User.php';
require_once APPLICATION_PATH . '/admin/models/Privilege.php';
class Admin_Model_Role extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'role';
    protected $_dependentTables = array('Admin_Model_User','Admin_Model_Privilege');

public function createRole ($role)
    {
        // create a new row
        $rowRole = $this->createRow();
        if ($rowRole) {
            // update the row values
            $rowRole->name = $role;
            $rowRole->save();
            //return the new user
            return $rowRole;
        } else {
            throw new Zend_Exception("Could not create role!");
        }
    }
    public function updateRole ($id, $role)
    {
        // fetch the user's row
        $rowRole = $this->find($id)->current();
        if ($rowRole) {
            // update the row values
            $rowRole->name = $role;
            $rowRole->save();
            //return the updated user
            return $rowRole;
        } else {
            throw new Zend_Exception("User update failed. User not found!");
        }
    }
    public function deleteRole ($id)
    {
        // fetch the user's row
        $rowRole = $this->find($id)->current();
        if ($rowRole) {
            $rowRole->delete();
        } else {
            throw new Zend_Exception("Could not delete user. User not found!");
        }
    }
    public static function getRoles ()
    {
        $roleModel = new self();
        $select = $roleModel->select();
        return $roleModel->fetchAll($select);
    }
    

}