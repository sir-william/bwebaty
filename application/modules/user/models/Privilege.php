<?php
require_once 'Zend/Db/Table/Abstract.php';
class Admin_Model_Privilege extends Zend_Db_Table_Abstract
{
    /**
     * The default table name
     */
    protected $_name = 'privilege';
    protected $_referenceMap = array(
    'Role' => array('columns' => array('role_id'), 
    'refTableClass' => 'Admin_Model_Role', 'refColumns' => array('id'), 
    'onDelete' => self::CASCADE, 'onUpdate' => self::RESTRICT));
    
    
    public function updatePrivileges ($role, $rows)
    {
        $this->deletePrivileges($role);
        foreach ($rows as $row) {
            $row['role_id'] = $role;
            $this->createPrivilege($row);
        }
    }
    public function createPrivilege ($row)
    {
        // create a new row
        $rowPrivilege = $this->createRow();
        if ($rowPrivilege) {
            // update the row values
            $rowPrivilege->role_id = $row['role_id'];
            $rowPrivilege->module = $row['module'];
            $rowPrivilege->controller = $row['controller'];
            $rowPrivilege->action = $row['action'];
            $rowPrivilege->save();
            //return the new user
            return $rowPrivilege;
        } else {
            throw new Zend_Exception("Could not create privielege!");
        }
    }
    public function deletePrivileges ($role)
    {
        $select = $this->select();
        $select->where('role_id = ?', $role);
        $rows = $this->fetchAll($select);
        if ($rows) {
            foreach ($rows as $row) {
                $row->delete();
            }
        }
    }
    
    public function getPrivileges($role){
        $select = $this->select();
        $select->where('role_id = ?', $role);
        $rows = $this->fetchAll($select);	
        return $rows;
    }
}