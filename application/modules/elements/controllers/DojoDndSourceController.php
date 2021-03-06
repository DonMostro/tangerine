<?php

class Elements_DojoDndSourceController extends Elements_BaseController
{
    /**
     * 
     * @var Zend_Db_Table_Abstract
     */
    protected $_model;
    

    public function indexAction()
    {
        $r = $this->getRequest();

        $this->view->selectedTitle =  $r->getParam('selectedTitle', 'Seleccionados');
        $this->view->unselectedTitle =  $r->getParam('unselectedTitle', 'No seleccionados');
        $this->view->saveUnselected = $r->getParam('saveUnselected', false);
        $this->view->editable = $r->getParam('editable', false);//@todo implement me in view
        $this->view->droppable = $r->getParam('droppable', false);//@todo implement me in view
        
        
        $this->view->unselectedTarget = $this->view->target;
        
        if ($this->view->saveUnselected) {
            $this->view->unselectedTarget = 'data[tangerineUnselected]';
        }
        
        
        $this->view->unselectedItems = array();
        $this->view->selectedItems = array();
        
        $selected = array();
        
        if ($r->getParam('table')) {
            $this->_model = $r->getParam('table');
            $this->_model = new $this->_model();
            $id = $r->getParam('tablePk') ? $r->getParam('tablePk') : 'id';
            
            $primary = $this->_model->info(Zend_Db_Table::PRIMARY);
            $primary = $primary[1];
            
            if ($r->getParam('tableMethod')) {
                $method = $r->getParam('tableMethod');
                $select = $this->_model->$method();
                $title = "title";
            } else {
                $select = $this->_model->select();
            }
            if (method_exists($select, "__toString")) { Debug::writeBySettings($select->__toString(), 'query_log'); 
            }
            $rows = $this->_model->fetchAll($select); //Query para pintar, sin seleccionar, todas las opciones disponibles.
            
            $value = $r->getParam('value');
            
            if ($r->getParam('value') && !is_array($r->getParam('value'))) {
                $value = json_decode($r->getParam('value'));
            }
            
             
            foreach ($rows as $row) {
                $selected = is_array($value) && in_array($row->$primary, $value);
                
                if ($r->getParam('tableField')) {
                    if ($selected) {
                        $this->view->selectedItems[$row[$id]] = $row[$r->getParam('tableField')];
                    } else {
                        $this->view->unselectedItems[$row[$id]] = $row[$r->getParam('tableField')];
                    }
                } else if ($r->getParam('field')) {
                    if ($selected) {
                        $this->view->selectedItems[$row[$id]] = $row[$r->getParam('field')];
                    } else {
                        $this->view->unselectedItems[$row[$id]] = $row[$r->getParam('field')];
                    }
                } else {
                    if ($selected) {
                        $this->view->selectedItems[$row[$id]] = $row["title"];
                    } else {
                        $this->view->unselectedItems[$row[$id]] = $row["title"];
                    }
                }
            }
            
        } else {
            if (!$r->getParam('value')) {
                $value = (isset($request->{$r->getParam('target')})) ? $request->{$r->getParam('target')} : null;
            } else {
                $value = $r->getParam('value');
            }
            
            $options = "";
            $rows = $r->getParam('list') ? explode(",", $r->getParam('list')) : array();
            
            foreach ($rows as $row) {
                $selected = $row == $value;
                if ($selected) {
                    $this->view->selectedItems[$row] = $row;
                } else {
                    $this->view->unselectedItems[$row] = $row;
                }
            }
        }
    }
}
