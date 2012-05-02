<?php

/**
 * Validation Textarea Dojo
 *
 * Requiere JS auxiliar, no es elemento nativo Dojo
 *
 *
 * @category Zwei
 * @package Zwei_Admin
 * @subpackage Elements
 * @version $Id:$
 * @since 0.1
 */



class Zwei_Admin_Elements_DojoValidationTextarea extends Zwei_Admin_Elements_Element
{
	protected $visible;
	protected $edit;
	protected $name;
	protected $target;
	protected $value;
	protected $params;

	/**
	 * Despliegue del elemento en formulario editable
	 * @param $i
	 * @param $j
	 * @param $display
	 * @return string HTML
	 */

	public function edit($i,$j,$display="block"){

		$readonly = @$this->params['READONLY'] ? "readonly" : '';
		$disabled = @$this->params['DISABLED'] ? "disabled" : '';
		$required = @$this->params['REQUIRED'] ? "required=\"true\"" : '';
		$regexp = isset($this->params['REG_EXP']) ? "RegExp=\"{$this->params['REG_EXP']}\"" : '';
		$invalid_message= isset($this->params['INVALID_MESSAGE']) ? "invalidMessage=\"{$this->params['INVALID_MESSAGE']}\"" : '';
		$prompt_message= isset($this->params['PROMPT_MESSAGE']) ? "promptMessage=\"{$this->params['PROMPT_MESSAGE']}\"" : '';
		$maxlength = isset($this->params['MAXLENGTH']) ? "maxlength=\"{$this->params['MAXLENGTH']}\"" : '';
		$trim = isset($this->params['TRIM']) ? "trim=\"true\"" : '';

		return "<textarea id=\"edit{$i}_{$j}\" name=\"$this->target[$i]\" dojoType=\"dijit.form.ValidationTextarea\" $readonly $disabled $required $regexp $trim $invalid_message $prompt_message $maxlength>".str_replace('"','&quot;',$this->value)."</textarea>";

	}

	/**
	 * obtener valor del formulario
	 * @param $value
	 * @param $i
	 * @return unknown_type
	 */


	public function get($value,$i=0){
		return $value;
	}

}

?>
