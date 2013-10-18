<?php
/**
 * Controlador de Datos
 *
 * Controlador principal que interactúa con capa de datos para operaciones CRUD, 
 * recibe parametros por $_REQUEST y devuelve datos y/o mensajes en formatos como json, xml o excel
 *
 * @package Controllers
 * @version $Id:$
 * @since 0.1
 */

class CrudRequestController extends Zend_Controller_Action
{
    /**
     * Información de usuario Instancia Singleton.
     * @var Zend_Auth
     */
    private $_user_info;
    /**
     * Instancia de $_REQUEST, en este caso preferible a Zend_Controller_Request ya que este no permite manejar un <pre>$_REQUEST['action']</pre> al ser el índice reservado.
     * @var Zwei_Utils_Form
     */
    private $_form;
    /**
     * Arreglo que será parte de la respuesta en json, Dojo data store, u otro formato a definir.
     * @var array
     */
    private $_response_content = array();
    
    /**
     * Modelo sobre el cual se trabajará.
     * @var Zwei_Db_Table
     */
    private $_model;
    
    /**
     * Archivo XML para obtener atributos adicionales y validar permisos
     * @var Zwei_Admin_Xml
     */
    private $_xml;

    public function init()
    {
       
        $this->_form = new Zwei_Utils_Form();
        
        if (Zwei_Admin_Auth::getInstance()->hasIdentity()) {
            $this->_user_info = Zend_Auth::getInstance()->getStorage()->read();
        } else if (isset($this->_form->format) && $this->_form->format == 'json') {
            $this->_helper->ContextSwitch
            ->setAutoJsonSerialization(false)
            ->addActionContext('index', 'json')
            ->initContext();
            $data = array( 'id'=>'0',
                               'state'=>'AUTH_FAILED',
                               'message'=>'Su sesión ha expirado, por favor vuelva a ingresar.',
                               'todo'=>'goToLogin');
            $this->view->content = Zend_Json::encode($data);
            $this->render('index');
        } else {
            $this->_redirect('admin/login');
        }
        $this->_helper->layout()->disableLayout();
        
        if (isset($this->_form->p)) {
            $file = Zwei_Admin_Xml::getFullPath($this->_form->p);
            $this->_xml = new Zwei_Admin_Xml($file, 0, 1);
        }
    }
    
    /**
     * Retorna un json a partir de un objeto modelo,
     * enviar nombre de clase modelo separada por "_" y sin sufijo "Model",
     * ej: enviar "model=solicitud_th" en lugar de "model=SolicitudThModel"
     * @return excel|json
     */
    
    public function indexAction()
    {
        
        /**
         * [TODO] Validar segun perfil de usuario autorizado a obtener estos datos
         */
        
        if (isset($this->_form->format) && $this->_form->format == 'json') {
            $this->_helper->ContextSwitch
            ->setAutoJsonSerialization(false)
            ->addActionContext('index', 'json')
            ->initContext();
        }
        
        $classModel = $this->getRequest()->getParam('model');
        if (class_exists($classModel)) {
            $data = array();
            /**
             * 
             * @var Zwei_Db_Table
             */
            $this->_model = new $classModel();
            $this->view->collection = array();
            
            if (Zwei_Admin_Auth::getInstance()->hasIdentity()) {
                if (isset($this->_form->action)) {
                    $where = array();
                    
                    foreach ($_FILES as $i => $v) {
                        $tmpTargets = array_keys($v['name']);
                        $target = $tmpTargets[0];
                        $path = !empty($this->_form->pathdata[$target]) ? 
                            $this->_form->pathdata[$target] : 
                            ROOT_DIR . '/public/upfiles';
                        
                        $element = $this->_xml->getElements("@target='$target'");
                        Debug::write('this->_xml');
                        Debug::write($element);
                        $infoFiles = $this->_form->upload($i, $path);
                        if ($infoFiles) {
                            $j = 0;
                            foreach ($infoFiles as $k => $l) {
                                $this->_form->data[$k] = $l['filename'];
                                if ($element[$j]->existsChildren('thumb')) {
                                    foreach ($element[$j]->thumb as $t) {
                                        $this->createThumb($t, $l, $path);
                                    }
                                }
                                $j++;
                            }
                        } else {
                            Debug::write("error al subir archivo a $path");
                        }
                    }
                    
                    if (isset($this->_form->deletedata )) {
                        foreach ($this->_form->deletedata as $i => $v) {
                            if (!@unlink($path. $v)) {
                                Debug::write("no se pudo borrar " . $this->_form->pathdata[$i] . $v);
                            }
                            $this->_form->data[$i] = '';
                        }
                    }
                    
                    
                    if ($this->_form->action == 'add') {
                        foreach ($this->_form->data as $i=>$v) {
                            $data[$i] = $v;
                        }
                        
                        try {
                            $response = $this->_model->insert($data);
                            if ($response) {
                                $this->_response_content['state'] = 'ADD_OK';
                            } else {
                                $this->_response_content['state'] = 'ADD_FAIL';
                            }
                        } catch (Zend_Db_Exception $e) {
                            $this->_response_content['state'] = 'ADD_FAIL';
                            Zwei_Utils_Debug::write("Zend_Db_Exception:{$e->getMessage()},Code:{$e->getCode()}");
                        }
                    } else if ($this->_form->action == 'delete') {
                        foreach ($this->_form->primary as $i => $v) {
                            $where[] = $this->_model->getAdapter()->quoteInto($this->_model->getAdapter()->quoteIdentifier($i)." = ?", $v);
                        }
                        if (count($where) == 1) $where = $where[0];
                        
                        $response = $this->_model->delete($where);
                        if ($response) {
                            $this->_response_content['state'] = 'DELETE_OK';
                        } else {
                            $this->_response_content['state'] = 'DELETE_FAIL';
                        }
                        //Zwei_Utils_Debug::write($response);
                    } else if ($this->_form->action == 'edit') {
                        foreach ($this->_form->primary as $i => $v) {
                            $where[] = $this->_model->getAdapter()->quoteInto($this->_model->getAdapter()->quoteIdentifier($i)." = ?", $v);
                        }
                        if (count($where) == 1) $where = $where[0];
                        
                        foreach ($this->_form->data as $i=>$v) {
                            $data[$i] = $v;
                        }
                        
                        try {
                            $response = $this->_model->update($data, $where);
                            if ($response) {
                                $this->_response_content['state'] = 'UPDATE_OK';
                            } else {
                                $this->_response_content['state'] = 'UPDATE_FAIL';
                            }
                        } catch (Zend_Db_Exception $e) {
                            $this->_response_content['state'] = 'UPDATE_FAIL';
                            Debug::write("Zend_Db_Exception:{$e->getMessage()},Code:{$e->getCode()}|model:$classModel|".$e->getTraceAsString());
                        }
                        //Zwei_Utils_Debug::write($response);
                    }
    
                    
                    
                    $this->_response_content['todo'] = $this->_model->getAjaxTodo();
                    $this->_response_content['more'] = $this->_model->getMore();
                    
                    
                } else {
                    //[TODO] validar permisos para listar
                    $oDbObject = new Zwei_Db_Object($this->_form);
                    $oSelect = $oDbObject->select();
                    
                    if (is_a($oSelect, "Zend_Db_Table_Select") || is_a($oSelect, "Zend_Db_Select")) {
                        $adapter = $this->_model->getZwAdapter();
                        
                        if (isset($adapter) && !empty($adapter)) $this->_model->setAdapter($adapter);
                    
                        $data = $this->_model->fetchAll($oSelect);
                        $paginator = Zend_Paginator::factory($oSelect);
                        $numRows = $paginator->getTotalItemCount();
                    } else {
                        $data = $this->_model->fetchAll();
                    }
                    $i = 0;
                     
                    //Si es necesario se añaden columnas o filas manualmente que no vengan del select original
                    if (method_exists($this->_model, 'overloadDataList') && $this->_model->overloadDataList($data)) {
                        $data = $this->_model->overloadDataList($data);
                        $numRows = count($data);
                    }
                    
                    //si ?format=excel exportamos el rowset a excel
                    if (isset($this->_form->format) && $this->_form->format == 'excel') {
                        $this->_helper->layout->disableLayout();
                        $this->_helper->viewRenderer->setNoRender();
                    
                        $table = new Zwei_Utils_Table();
                    
                        if ($this->_form->excel_formatter != 'csv') {
                            header('Content-type: application/vnd.ms-excel');
                            header("Content-Disposition: attachment; filename={$this->_form->model}.xls");
                            header("Pragma: no-cache");
                            header("Expires: 0");
                    
                            if (isset($this->_form->p)) {
                                $content = $table->rowsetToHtml($data, $this->_form->p);
                            } else {
                                $content = $table->rowsetToHtml($data);
                            }
                            echo $content;
                        } else {
                            header("Pragma: public");
                            header("Expires: 0");
                            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                            header("Content-Type: application/force-download");
                            header("Content-Type: application/octet-stream");
                            header("Content-Type: application/download");
                            header("Content-Disposition: attachment; filename={$this->_form->model}.csv");
                            header('Content-Encoding: UTF-8');
                            header('Content-type: text/csv; charset=UCS-2LE');
                    
                    
                            if (isset($this->_form->p)) {
                                $content = $table->rowsetToCsv($data, $this->_form->p);
                            } else {
                                $content = $table->rowsetToCsv($data);
                            }
                            echo  chr(255) . chr(254) . mb_convert_encoding($content, 'UCS-2LE', 'UTF-8');
                        }
                    
                        exit();
                    }
                }
            } else {
                $data = array();
            }
            
            if (count($this->_response_content) > 0) {
                $this->_response_content['message'] = $this->_model->getMessage();
                $data = array( 'id' => '0',
                               'state' => $this->_response_content['state'],
                               'message' => $this->_response_content['message'],
                               'todo' => $this->_response_content['todo'],
                               'more' => $this->_response_content['more']);
                $content = Zend_Json::encode($data);
                $this->getResponse()
                ->setHeader('Content-Type', 'text/html'); //internet explorer needs this
            } else {
                $i = 0;
                $collection = array();
                foreach ($data as $rowArray) {
                    $collection[$i] = array();
                    foreach ($rowArray as $column => $value) {
                        if (!is_array($value)) {
                            $collection[$i][$column] = ((PHP_VERSION_ID >= 50400)) ? html_entity_decode($value) :  utf8_encode(html_entity_decode($value));
                        } else {
                            foreach ($value as $column2 => $value2) {
                                $collection[$i][$column][$column2] = (PHP_VERSION_ID >= 50400) ? html_entity_decode($value2) : utf8_encode(html_entity_decode($value2));
                            }
                        }
                    }
                    $i++;
                }
                //Zwei_Utils_Debug::write($str_collection);
                $id = method_exists($this->_model, 'info') ? $this->_model->info(Zend_Db_Table_Abstract::PRIMARY) : 'id';

                if ((!is_array($id) || count($id) == 1)) {
                    //FIXME
                    if (is_array($id)) {
                        $arrayValues = array_values($id);
                        $id = $arrayValues[0]; 
                    }
                    $content = new Zend_Dojo_Data($id, @$collection);
                } else {
                    /*
                     * En caso de que no exista ninguna PK simple, inventamos un ID aca para que funcione dojo.data.ItemFileStore
                     */
                    if (!isset($collection[0]['AdmFakeId'])) {
                        for($j=0;$j<$i;$j++) {
                            $collection[$j]['AdmFakeId']=$j;
                        }
                    }
                    
                    $content = new Zend_Dojo_Data('AdmFakeId', @$collection);
                }

                /**
                 * Si esta especificado $this->_model->_label se especifica el ÍNDICE del atributo label, standard dojo store,
                 * Si esta especificado $this->_model->_labels se especifica el ARRAY de labels, NO standard dojo store pero necesario para algunos casos.
                 */
                if (method_exists($this->_model, 'getLabel') && $this->_model->getLabel()) {
                    $content->setLabel($this->_model->getLabel());
                } 
                
                if (method_exists($this->_model, 'getLabels') && $this->_model->getLabels()) {
                    $content->setMetadata(array("labels" => $this->_model->getLabels()));
                }
                
                if (method_exists($this->_model, 'getTitle') && $this->_model->getTitle()) {
                    $content->setMetadata(array("title" => $this->_model->getTitle()));
                }
                
                if (isset($numRows)) $content->setMetadata('numRows', $numRows);
                $this->getResponse()
                ->setHeader('Content-Type', 'text/html');
                
            }
            $this->view->content = $content;

        }
    }//public function indexAction()

    public function multiUpdateAction()
    {
        require_once ADMPORTAL_APPLICATION_PATH .'/../library/PhpThumb/ThumbLib.inc.php';
        $r = $this->getRequest();
        $classModel = $r->getParam('model');

        $this->_model = new $classModel();
        Debug::write('........getparams........................');
        Debug::write($r->getParams());
        Debug::write($r->getParam('thumbdata'));
        
   //     $otro = $r->getParam('thumbdata');
    //    $otro1 = $otro['url_logo_zweicom'].[0].['width'];
        //$thumb['url_logo_zweicom'] = $r->getParam('thumbdata');
        //$width= $thumb['url_logo_zweicom'];
        //$otro = $width['url_logo_zweicom'];
        //$width_ = $width['url_logo_zweicom'];
          
          
          
        /*   Debug::write('urlLogoZweicom');
        Debug::write($urlLogoZweicom);
        $index[] = $urlLogoZweicom[0];
        Debug::write('index');
        Debug::write($index);
        $width= $index['width'];
       */
  //      Debug::write('otro');
    //    Debug::write($otro1);
       
        
        
        
        $updated = 0;
        $failed = 0;
        $message = '';
        
        $count = 0;
        foreach ($_FILES as $data => $v) {
            $tmpTargets = array_keys($v['name']);
            $target = $tmpTargets[$count];
            $path = $this->_form->pathdata[$target];
            $infoFiles = $this->_form->upload($data, $path);
            
            if ($infoFiles) {
                foreach ($infoFiles as $id => $v) {
                    $id = str_replace("'", '', $id);
                    $row = $this->_model->find($id)->current();
                    $xmlChildren = new Zwei_Admin_Xml('<element>'.html_entity_decode($row->xml_children).'</element>');
                    
                    foreach ($xmlChildren->thumb as $child) {
                        
                        $this->createThumb($child, $v, $path);
                    }
                    //Se agrega nombre de archivo subido a array de actualizacion de datos
                    $this->_form->data[$id] = $v['filename'];
                    
                    
                  /*  $thumb = PhpThumbFactory::create($path."/".$v['filename']);
                    $thumb->resize($t->getAttribute('width'), $t->getAttribute('height'));
                    Debug::write('thumb');
                    Debug::write($thumb);*/
                }
            } else if ($v['size'] > 0) {
                Debug::write("error al subir archivo a $path");
            }
            $count++;
        }
        
        
        if (isset($this->_form->deletedata )) {
            foreach ($this->_form->deletedata as $id => $value) {
                if (!unlink($this->_form->pathdata[$id]. $value)) {
                    Debug::write("no se pudo borrar " . ROOT_DIR . '/upfiles/'. $value);
                }
                $this->_form->data[$id] = '';
            }
        }
        
        $i = 0;
        foreach ($this->_form->data as $id => $value) {
            $id = str_replace("'", '', $id);
            $row = $this->_model->find($id)->current();
            $row->value = $value;
            if ($row->save()) $updated++;
        }
        
        $message .= "Actualizados $updated registros";
        
        $data = array(
            'id'     => '0',
            'state'  => 0,
            'message'=> $message,
            'todo'   => '',
            'more'   => ''
        );
        
        $this->view->content = Zend_Json::encode($data);
    }
    
    /**
     * Genera las miniaturas a partir de información xml
     * 
     * @param Zwei_Admin_Xml $node
     * @param array $infoFile
     * @param string $path
     * @return GdThumb
     */
    protected function createThumb(Zwei_Admin_Xml $node, $infoFile, $path)
    {
        try {
            if (preg_match("/^\{ROOT_DIR\}(.*)$/", $node->getAttribute('path'), $matches)) {
                $thumbPath = ROOT_DIR . $matches[1];
            } else if (preg_match("/^\{APPLICATION_PATH\}(.*)$/", $node->getAttribute('path'), $matches)) {
                $thumbPath = APPLICATION_PATH . $matches[1];
            } else if ($node->getAttribute('path')){
                $thumbPath = $node->getAttribute('path');
            } else {
                $thumbPath = $path; 
            }
            
        
            //[TODO] cambiar configuracion del Autoloader en Bootstrap para no usar require_once
            require_once ADMPORTAL_APPLICATION_PATH . '/../library/PhpThumb/ThumbLib.inc.php';
        
            if (!file_exists($path)) mkdir($path, 0777, true);
            Debug::write($path);
            $thumb = PhpThumbFactory::create($path."/".$infoFile['filename']);
            $width = $node->getAttribute('width') ? $node->getAttribute('width') : 0;
            $height = $node->getAttribute('height') ? $node->getAttribute('height') : 0;
            
            $thumb->resize($width, $height);
            $thumb->save($thumbPath."/".$infoFile['filename']);
            Debug::write($thumb);
        } catch (Exception $e) {
            Debug::write($e->getMessage() . '-' . $e->getCode() . $e->getTraceAsString());
            return false;
        }
    }
    
}
