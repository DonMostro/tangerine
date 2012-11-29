<?php

/**
 * Modelo de datos personales, requiere sesion ACL iniciada
 *
 * @category Zwei
 * @package Models
 * @version $Id:$
 * @since 0.1
 *
 */

class PersonalInfoModel extends Zwei_Db_Table
{
    protected $_name = "acl_users";
    protected $_name_roles = "acl_roles";
    protected $_generate_pass = "user_name";
    protected $_acl;
    protected $_user_name;

    public function init()
    {
        if(!Zwei_Admin_Auth::getInstance()->hasIdentity()) $this->_redirect('index/login');
        $userInfo = Zend_Auth::getInstance()->getStorage()->read();
        $this->_acl = new Zwei_Admin_Acl($userInfo->user_name);
        $this->_user_name = $userInfo->user_name;
    }

    public function select()
    {
        $oSelect=new Zend_Db_Table_Select($this);
        $oSelect->setIntegrityCheck(false); //de lo contrario no podemos hacer JOIN
        $oSelect->from($this->_name)
        ->joinLeft($this->_name_roles, "$this->_name.acl_roles_id = $this->_name_roles.id", "role_name")
        ;
        $oSelect->where($this->getAdapter()->quoteInto('user_name = ?', $this->_user_name));
        return $oSelect;
    }

    /**
     * En el caso de crearse un usuario nuevo,
     * se genera la password repitiendo el nombre de usuario en md5
     * @return int
     */
    public function insert($data)
    {
        $data["password"] = md5($data[$this->_generate_pass]);
        try {
            $last_insert_id = parent::insert($data);
        } catch(Zend_Db_Exception $e) {
            if ($e->getCode() == '23000') {
                $this->setMessage('Nombre de Usuario en uso.');
                return false;
            } else {
                Zwei_Utils_Debug::write("error:".$e->getMessage()."code".$e->getCode());
            }
        }
        return $last_insert_id;
    }

    /**
     * Captura de excepciones posibles como nombre de usuario en uso
     */

    public function update($data, $where) {
        try {
            $update = parent::update($data, $where);
        } catch(Zend_Db_Exception $e) {
            if ($e->getCode()=='23000') {
                $this->setMessage('Nombre de Usuario en uso.');
                return false;
            } else {
                Zwei_Utils_Debug::write("error:".$e->getMessage()."code".$e->getCode());
            }
        }
        return $update;
    }
}
