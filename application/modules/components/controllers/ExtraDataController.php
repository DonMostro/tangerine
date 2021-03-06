<?php

class Components_ExtraDataController extends Zend_Controller_Action
{

    /**
     *
     * @var Gamelena_Db_Table
     */
    protected $_model = null;
    
    /**
     *
     * @var Gamelena_Admin_Acl
     */
    protected $_acl = null;
    
    /**
     *
     * @var Gamelena_Admin_Xml
     */
    protected $_xml = null;
    
    /**
     * Post Constructor
     *
     * @see Zend_Controller_Action::init()
     */
    public function init()
    {
        $r = $this->getRequest();
        $component = $r->getParam('p');
        $file = Gamelena_Admin_Xml::getFullPath($component);
        $this->_xml = new Gamelena_Admin_Xml($file, 0, 1);
        
        if ($this->_xml->getAttribute('target')) {
            $this->view->model = $this->view->editableModel = $this->_xml->getAttribute('target');
            $this->_model = new $this->view->model();
        } else {
            Console::debug("Modelo Offline");
        }
        
        $this->view->list = $this->_xml->getAttribute('list') === "true" ? true : false;
        $this->view->component = $component;
        $this->view->domPrefix = Gamelena_Utils_String::toVarWord($this->view->component);
        $this->view->extraDataParams = array();
        $this->view->idParentEditorName = null;
        
        $search = $r->getParam('search', array());
        
        //yes, se espera solo un elemento
        foreach ($search as $id => $s) {
            $this->view->idExtraDataName = $id;
            $this->view->idExtraDataValue = $s['value'] ? $s['value'] : '0';
        }
        
        $this->_acl = new Gamelena_Admin_Acl();
    }

    /**
     * Acción Listar Valores de Variables Extra Data
     *
     * @return void
     */
    public function indexAction()
    {
        $r = $this->getRequest();
        $this->initPermissions();
        
        $this->view->store = $this->view->list ? "store: {$this->view->domPrefix}extraDataStore," : "";
        $this->view->editableModel = $this->_xml->getAttribute('target');
        $this->view->idParentEditorName = $r->getParam('idParentEditorName');
    }

    /**
     * Inicializar flags de acceso para las vistas
     * 
     * @return void
     */
    private function initPermissions()
    {
        $component = $this->_xml->getAttribute('aclComponent');
        $aclComponent = $component ? $component : $this->getRequest()->getParam('p');
        
        $this->view->isAllowedAdd = $this->_acl->isUserAllowed($aclComponent, 'ADD') && $this->_xml->getAttribute('add') === 'true';
        $this->view->isAllowedDelete = $this->_acl->isUserAllowed($aclComponent, 'DELETE') && $this->_xml->getAttribute('add') === 'true';
    }


}

