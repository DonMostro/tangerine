<?php

class Components_GroupsModulesController extends Zend_Controller_Action
{

    /**
     * @var int
     *
     *
     */
    private $_aclModulesId = null;

    /**
     * @var int
     *
     *
     */
    private $_aclModulesItemId = null;
    
    /**
     * 
     * @var int
     */
    private $_aclGroupsId;
    
    /**
     *
     * @var array
     */
    private $_selected = array();
    
    /**
     * Post Constructor
     *
     * @see Zend_Controller_Action::init()
     *
     *
     */
    public function init()
    {
        if (!Zwei_Admin_Auth::getInstance()->hasIdentity()) $this->_redirect('admin/login');
        $this->view->aclModulesId = $this->_aclModulesId = $this->getRequest()->getParam('acl_modules_id');
        $this->view->aclModulesItemId = $this->_aclModulesItemId = $this->getRequest()->getParam('acl_modules_item_id');
        $this->view->aclGroupsId = $this->_aclGroupsId = $this->getRequest()->getParam('id');
        $this->view->model = 'AclGroupsModulesActionsModel';
    }

    public function indexAction()
    {
        
        
    }

    public function getModules($aclModulesId = null, $title = null)
    {
        $i = 0;
        $modules[$i] = array();
        
        $actions = array();
        $modulesModel = new DbTable_AclModules();
        $parent = $modulesModel->find($aclModulesId)->current();
        $modules[$i]['id'] = $parent->id;
        $modules[$i]['title'] = $title != null ? $title : $parent->title;
        
        
        $actions = $parent->findDependentRowset('DbTable_AclModulesActions');
        $modules[$i]['actions'] = $actions->toArray();
        
        $modules[$i] = (object) $modules[$i];
        
        $j = 0;
        foreach ($actions as $action) {
            $modules[$i]->actions[$j]['title'] = $action->findParentRow('DbTable_AclActions')->title;
            $modules[$i]->actions[$j]['selected'] = in_array($action->id, $this->_selected);
            $modules[$i]->actions[$j] = (object) $modules[$i]->actions[$j];
            $j++;
        }
        
        $select = $parent->select()->order('order');
        $childrens = $parent->findDependentRowset('DbTable_AclModules', null, $select);
        
        if ($childrens->count()) {
            foreach ($childrens as $child) {
                $module = $this->getModules($child->id);
                $modules[] = $module[0];
            }
        }
        
        
        return $modules;
    }

    public function editAction()
    {
        $selected = array();
        $gmaModel = new AclGroupsModulesActionsModel();
        
        $select = $gmaModel->select();
        $select->where($gmaModel->getAdapter()->quoteInto('acl_groups_id = ?', $this->_aclGroupsId));
        $select->where($gmaModel->getAdapter()->quoteInto('acl_modules_item_id = ?', $this->_aclModulesItemId));
        
        foreach ($gmaModel->fetchAll($select) as $row) {
            $this->_selected[] = $row->acl_modules_actions_id;
        }
        
        $this->view->mode = 'edit';
        $title = 'Campaña';
        $this->view->modules = $this->getModules($this->_aclModulesId, $title);
    }
}
