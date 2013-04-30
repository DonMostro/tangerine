<?php
/**
 * Extiende Zend_Db_Table guardando en log las acciones crear, modificar, eliminar
 *
 * @package Zwei_Db
 * @version $Id:$
 * @since 0.1
 */
require_once ('Zwei/Db/Table.php');
class Zwei_Db_TableLoggeable extends Zwei_Db_Table
{
    public function insert($data)
    {
    	$last_insert_id = parent::insert($data);  
        if ($last_insert_id) $this->log("Agregar", $last_insert_id);   
        return $last_insert_id;
    }
    
    public function update($data, $where)
    {
    	$update = parent::update($data, $where);
    	if (is_array ($where)) {
    		$where = array_values($where);
    		$where = (count($where) == 1) ? $where[0] : print_r($where, true);
    		$where = print_r($where, true);
    	} 
    	if ($update) $this->log("Editar", $where);
        return $update;
    }
    
    public function delete($where)
    {
    	$delete = parent::delete($where);
        if ($delete) $this->log("Eliminar", $where);
        return $delete;
    }   
    
    public function log($action, $condition) {
        if (is_array($condition)) $condition = print_r($condition, true);
        if (self::$_defaultLogMode) {
            $logBook = new LogBookModel();
            
            $logData = array (
                "user" => $this->_user_info->user_name,
                "acl_roles_id" => $this->_user_info->acl_roles_id,
                "table" => $this->_name,
                "action" => $action,
                "ip" => $_SERVER['REMOTE_ADDR'],
                "stamp" => date("Y-m-d H:i:s")
            ); 
            
            if ($condition) $logData["condition"] = (string) $condition; 
            $logBook->insert($logData);
        }       
    }   
}
