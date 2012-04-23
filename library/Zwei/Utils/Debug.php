<?php
/**
 * Almacenar mensajes en archivos de texto plano para log, debugging y seguimiento de errores
 * 
 * @package Zwei_Utils
 * @version $Id:$
 * @since 0.1
 * 
 * @example: Zwei_Utils_Debug::write($mensaje)
 *
 *
 */
class Zwei_Utils_Debug
{
	
  /**
   * Escribe el reporte de error en un archivo de texto plano llamado debug
   * @param $message
   */	
    function write($message = null, $file = "../log/debug")
    {
        $trace = debug_backtrace() ;
	    if  ($message !== null ){
	        $message = $trace[0]['file'].'['.$trace[0]['line'].']['.strftime('%Y-%m-%d %H:%M:%S').']: '.print_r($message, 1);
	    } else {
	        $message = "[".strftime('%Y-%m-%d %H:%M:%S')."]\n".print_r($trace, 1);
	    }
	    $ff = fopen($file, "a");
	    fwrite($ff,"$message\r\n");
	    fclose($ff);
	}
	
	function writeBySettings($message, $settings_id, $settings_value='SI', $file= "../log/debug")
	{
        $oSettings = new SettingsModel();
        try {
            $oSettingsSelect = $oSettings->select()->where('id = ?', $settings_id);
            $aSettings = $oSettings->fetchAll($oSettingsSelect);
            if ($aSettings[0]['value'] == $settings_value) {
                $trace = debug_backtrace();
            	if ($message !==null ) {
	               $message = $trace[0]['file'].'['.$trace[0]['line'].']['.strftime('%Y-%m-%d %H:%M:%S').']: '.print_r($message, 1);
		        } else {
		           $message = "[".strftime('%Y-%m-%d %H:%M:%S')."]\n".print_r($trace, 1);
		        }
		        $ff = fopen($file, "a");
		        fwrite($ff, "$message\r\n");
		        fclose($ff);
            }
        } catch (Zend_Db_Exception $e) {
            Zwei_Utils_Debug::write("Error {$e->getCode()} {$e->getMessage()}");
        } 
	}	
}
