<?php
require_once 'Zend/Db/Table/Abstract.php';
class Admin_Model_User extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'users';
    protected $_referenceMap = array(
    'Role' => array('columns' => array('role_id'), 
    'refTableClass' => 'Admin_Model_Role', 'refColumns' => array('id'), 
    'onDelete' => self::CASCADE, 'onUpdate' => self::RESTRICT));
    public function createUser ($username, $password, $firstName, $lastName, 
    $role)
    {
        // create a new row
        $rowUser = $this->createRow();
        if ($rowUser) {
            // update the row values
            $rowUser->username = $username;
            $rowUser->password = md5($password);
            $rowUser->first_name = $firstName;
            $rowUser->last_name = $lastName;
            $rowUser->role = $role;
            $rowUser->save();
            //return the new user
            return $rowUser;
        } else {
            throw new Zend_Exception("Could not create user!");
        }
    }
    public static function getUsers ()
    {
        $userModel = new self();
        $select = $userModel->select();
        $select->from('users','*');
        $select->setIntegrityCheck(false);
        $select->order(array('last_name', 'first_name'));
        $select->join('role', 'role.id=users.role_id','name');
        return $userModel->fetchAll($select);
    }
    public function updateUser ($id, $username, $firstName, $lastName, $role)
    {
        // fetch the user's row
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            // update the row values
            $rowUser->username = $username;
            $rowUser->first_name = $firstName;
            $rowUser->last_name = $lastName;
            $rowUser->role = $role;
            $rowUser->save();
            //return the updated user
            return $rowUser;
        } else {
            throw new Zend_Exception("User update failed. User not found!");
        }
    }
    public function deleteUser ($id)
    {
        // fetch the user's row
        $rowUser = $this->find($id)->current();
        if ($rowUser) {
            $rowUser->delete();
        } else {
            throw new Zend_Exception("Could not delete user. User not found!");
        }
    }
}