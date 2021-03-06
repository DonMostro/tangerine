<?php

class AclSessionOnlineModel extends DbTable_AclSession
{

    /**
     * Tabla de usuarios
     * 
     * @var string
     */
    private $_nameAclUsers = 'acl_users';

    /**
     * Tabla de perfiles
     * 
     * @var string
     */
    private $_nameAclRoles = 'acl_roles';

    /**
     *
     * @return Zend_Db_Table_Select
     * @see Zend_Db_Table_Abstract::select()
     */
    public function select($withFromPart = self::SELECT_WITHOUT_FROM_PART)
    {
        $select = parent::select($withFromPart);
        $select->setIntegrityCheck(false);
        $config = Gamelena_Controller_Config::getOptions();
        
        $select->from(
            $this->_name, array(
                'id',
                'ip',
                'user_agent',
                'modified'
            )
        );
        $select->joinLeft(
            $this->_nameAclUsers, 
            "$this->_name.acl_users_id=$this->_nameAclUsers.id", 
            array(
                        'user_name',
                        'email'
                )
        );
                $select->joinLeft(
                    $this->_nameAclRoles, 
                    "$this->_nameAclUsers.acl_roles_id=$this->_nameAclRoles.id", 
                    array(
                        'role_name'
                    )
                );
        
                if ($this->_user_info->acl_roles_id != ROLES_ROOT_ID) {
                    $select->where("$this->_name.acl_roles_id <> '1'");
                }
        
                $modifiedDelayed = time() - 32;
                $select->where(
                    $this->getAdapter()->quoteInto("$this->_name.modified > ?", $modifiedDelayed)
                );
                $select->where("$this->_name.acl_users_id <> '0'");
                $select->order("$this->_name.modified DESC");
        
        
                return $select;
    }

    /**
     *
     * @param Zend_Db_Table_Rowset $data            
     * @return array
     *
     * @see Gamelena_Db_Table::overloadDataList()
     */
    public function overloadDataList($data)
    {
        $i = 0;
        $data = $data->toArray();
        $config = Gamelena_Controller_Config::getOptions();
        
        foreach ($data as $d) {
            // si la última actividad fue hace más de 10 segundos entonces ya no
            // está logueado,
            // le damos 2 segundos más como margen de error (12 en total)
            $timeout = $d['modified'] + $config->gamelena->session->timeout;
            $data[$i]['expires'] = date('Y-m-d H:i:s', $timeout);
            $data[$i]['modified'] = date('Y-m-d H:i:s', $d['modified']);
            $i ++;
        }
        
        return $data;
    }

    /**
     * Deletes existing rows.
     *
     * @param  array|string $where SQL WHERE clause(s).
     * @return int          The number of rows deleted.
     * @see    Gamelena_Db_Table::delete()
     */
    public function delete($where)
    {
        $aWhere = self::whereToArray($where);
        if ($aWhere['id'] == Zend_Session::getId()) {
            $this->setMessage(
                "No puede borrar su propia sesión. <br/> Para cerrar sesión use 'Salir'."
            );
            return false;
        }
        return parent::delete($where);
    }
    
    /**
     * 
     * @param int $aclUsersId
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function findByAclUsersId($aclUsersId)
    {
        return $this->fetchAll($this->getAdapter()->quoteInto("$this->_name.acl_users_id = ? ", $aclUsersId));
    }
}

