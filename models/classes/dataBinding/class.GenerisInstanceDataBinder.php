<?php
/**  
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 * 
 * Copyright (c) 2002-2008 (original work) Public Research Centre Henri Tudor & University of Luxembourg (under the project TAO & TAO2);
 *               2008-2010 (update and modification) Deutsche Institut für Internationale Pädagogische Forschung (under the project TAO-TRANSFER);
 *               2009-2012 (update and modification) Public Research Centre Henri Tudor (under the project TAO-SUSTAIN & TAO-DEV);
 * 
 */

/**
 * A data binder focusing on binding a source of data to a generis instance
 *
 * @access public
 * @author Jerome Bogaerts, <jerome@taotesting.com>
 * @package tao
 
 */
class tao_models_classes_dataBinding_GenerisInstanceDataBinder
    extends tao_models_classes_dataBinding_AbstractDataBinder
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * A target Resource.
     *
     * @access private
     * @var Resource
     */
    private $targetInstance = null;

    // --- OPERATIONS ---

    /**
     * Creates a new instance of binder.
     *
     * @access public
     * @author Jerome Bogaerts, <jerome@taotesting.com>
     * @param  Resource targetInstance The
     * @return mixed
     */
    public function __construct( core_kernel_classes_Resource $targetInstance)
    {
        
        $this->targetInstance = $targetInstance;
        
    }

    /**
     * Returns the target instance.
     *
     * @access protected
     * @author Jerome Bogaerts, <jerome@taotesting.com>
     * @return core_kernel_classes_Resource
     */
    protected function getTargetInstance()
    {
        $returnValue = null;

        
        $returnValue = $this->targetInstance;
        

        return $returnValue;
    }

    /**
     * Simply bind data from the source to a specific generis class instance.
     *
     * The array of the data to be bound must contain keys that are property
     * The repspective values can be either scalar or vector (array) values or
     * values.
     *
     * - If the element of the $data array is scalar, it is simply bound using
     * - If the element of the $data array is a vector, the property values are
     * with the values of the vector.
     *
     * @access public
     * @author Jerome Bogaerts, <jerome@taotesting.com>
     * @param  array data An array of values where keys are Property URIs and values are either scalar or vector values.
     * @return mixed
     */
    public function bind($data)
    {
        $returnValue = $this->getTargetInstance();

        try {
            
            $properties = array();
            
            foreach ($data as $propertyUri => $value) {
                if ($propertyUri == RDF_TYPE) {
                    $this->bindTypes($value);
                } else {
                    $property = new core_kernel_classes_Property($propertyUri);
                    if ($property->exists()) {
                        $properties[$propertyUri] = $value;
                    }
                }
            }
	        
	        $oldData = $this->getOldData(array_keys($properties));

	        $toAdd = array();
	        foreach ($properties as $propertyUri => $propertyValue) {
    		    $property = new core_kernel_classes_Property( $propertyUri );
    		    $newValues = is_array($propertyValue) ? $propertyValue : 
                    (is_string($propertyValue) ? array($propertyValue) : array());
    		    
    		    foreach (array_diff($oldData[$propertyUri], $newValues) as $toRemove) {
    		        $this->getTargetInstance()->removePropertyValue($property, $toRemove);
    		    }
    		    
    		    $addValues = array_diff($newValues, $oldData[$propertyUri]);
    		    if (!empty($addValues)) {
    		        $toAdd[$propertyUri] = $addValues;
    		    }
	        }
	        $this->getTargetInstance()->setPropertiesValues($toAdd);
	        
        } catch (common_Exception $e){
        	$msg = "An error occured while binding property values to instance '': " . $e->getMessage();
        	$instanceUri = $instance->getUri();
        	throw new tao_models_classes_dataBinding_GenerisInstanceDataBindingException($msg);
        }
        

        return $returnValue;
    }
    
    public function getOldData($propertyUris) {
        $propertiesValues = $this->getTargetInstance()->getPropertiesValues($propertyUris);
        
        $data = array();
        foreach ($propertyUris as $uri) {
            $data[$uri] = array();
            if (isset($propertiesValues[$uri])) {
                foreach ($propertiesValues[$uri] as $element) {
                    if ($element instanceof core_kernel_classes_Literal) {
                        $data[$uri][] = $element->__toString();
                    } elseif ($element instanceof core_kernel_classes_Resource) {
                        $data[$uri][] = $element->getUri();
                    }
                }
            }
        }
        return $data;
    }
    
    public function bindTypes($types) {
        $newTypeUris = is_array($types) ? $types : array($types);
        $oldTypeUris = array();
        foreach ($this->getTargetInstance()->getTypes() as $resource) {
            $oldTypeUris[] = $resource->getUri();
        }
        
        foreach (array_diff($oldTypeUris, $newTypeUris) as $toRemove) {
            common_Logger::i('Unbound old Type '.$toRemove);
            $this->getTargetInstance()->removeType(new core_kernel_classes_Class($toRemove));
        }
        
        foreach (array_diff($newTypeUris, $oldTypeUris) as $toAdd) {
            common_Logger::i('Bound new Type '.$toAdd);
            $this->getTargetInstance()->setType(new core_kernel_classes_Class($toAdd));
        }
    }

}
