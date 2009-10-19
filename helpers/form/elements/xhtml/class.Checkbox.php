<?php

error_reporting(E_ALL);

/**
 * Generis Object Oriented API -
 *
 * $Id$
 *
 * This file is part of Generis Object Oriented API.
 *
 * Automatically generated on 30.09.2009, 14:21:07 with ArgoUML PHP module 
 * (last revised $Date: 2008-04-19 08:22:08 +0200 (Sat, 19 Apr 2008) $)
 *
 * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
 * @package tao
 * @subpackage helpers_form_elements_xhtml
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include tao_helpers_form_elements_Checkbox
 *
 * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
 */
require_once('tao/helpers/form/elements/class.Checkbox.php');

/* user defined includes */
// section 127-0-1-1-3ed01c83:12409dc285c:-8000:00000000000019EC-includes begin
// section 127-0-1-1-3ed01c83:12409dc285c:-8000:00000000000019EC-includes end

/* user defined constants */
// section 127-0-1-1-3ed01c83:12409dc285c:-8000:00000000000019EC-constants begin
// section 127-0-1-1-3ed01c83:12409dc285c:-8000:00000000000019EC-constants end

/**
 * Short description of class tao_helpers_form_elements_xhtml_Checkbox
 *
 * @access public
 * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
 * @package tao
 * @subpackage helpers_form_elements_xhtml
 */
class tao_helpers_form_elements_xhtml_Checkbox
    extends tao_helpers_form_elements_Checkbox
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method newOperation
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @return string
     */
    public function render()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-3ed01c83:12409dc285c:-8000:00000000000019FC begin
		
		$i = 0;
		$returnValue .= "<span class='form_desc'>".$this->getDescription()."</span><br />";
		foreach($this->options as $optionId => $optionLabel){
			 $returnValue .= "<input type='checkbox' name='{$optionId}' id='{$this->name}_{$i}' ";
			 $returnValue .= $this->renderAttributes();
			 if($this->value == $optionId){
			 	$returnValue .= " checked='checked' ";	
			 }
			 $returnValue .= " />&nbsp;<span class='elt_desc'>{$optionLabel}</span><br />";
			 $i++;
		}
		
        // section 127-0-1-1-3ed01c83:12409dc285c:-8000:00000000000019FC end

        return (string) $returnValue;
    }

} /* end of class tao_helpers_form_elements_xhtml_Checkbox */

?>