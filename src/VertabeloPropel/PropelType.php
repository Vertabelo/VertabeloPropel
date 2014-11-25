<?php

/* 
 * Copyright 2014 vertabelo.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

namespace Vertabelo\VertabeloPropel;

use Vertabelo\VertabeloPropel\TypeMapping;

class PropelType {
    public $name;
    public $size;
    public $precision;
    
    public function __construct($name, $size, $precision) {
        $this->name = $name;
        $this->size = $size;
        $this->precision = $precision;
    }
    
    private static function getVertabeloTypeName($type) {
        $typeName = preg_replace("#\([0-9]+\)#", "(%)", $type, -1);
        $typeName = preg_replace("#\([0-9]+,[0-9]+\)#", "(%,%)", $typeName, -1);
        $typeName = preg_replace("#\([0-9]+ byte\)#", "(% byte)", $typeName, -1);
        $typeName = preg_replace("#\([0-9]+ char\)#", "(% char)", $typeName, -1);
        
        return $typeName;
    }
    
    private static function getSize($type) {
        preg_match("#\(([0-9]+)[,\) ]#", $type, $sizeMatches);
        
        if (count($sizeMatches) > 1) {
            return $sizeMatches[1];
        } else {
            return NULL;
        }
    }
    
    private static function getPrecision($type) {
        preg_match("#,([0-9]+)\)#", $type, $precisionMatches);
        
        if (count($precisionMatches) > 1) {
            return $precisionMatches[1];
        } else {
            return NULL;
        }
    }
    
    public static function fromVertabeloType($type) {
        $vertabeloTypeName = PropelType::getVertabeloTypeName($type);
        $typeMapping = (new TypeMapping())->getTypeMapping();
        $propelTypeName = $typeMapping[$vertabeloTypeName];
        
        $size = PropelType::getSize($type);
        $precision = PropelType::getPrecision($type);        
        
        return new PropelType($propelTypeName, $size, $precision);
    }
}