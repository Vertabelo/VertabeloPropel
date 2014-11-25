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

use SimpleXMLElement;
use Vertabelo\VertabeloPropel\TypeMapping;

class Vertabelo2PropelConverter {
    private $output;
    
    public function __construct($output) {
        $this->output = $output;
    }
    
    protected function createDatabase($dbName, $defaultIdMethod) {
        $this->propelDatabase = new SimpleXMLElement('<database></database>');
        $this->propelDatabase->addAttribute('name', $dbName);
        $this->propelDatabase->addAttribute('defaultIdMethod', $defaultIdMethod);
        
        return $this->propelDatabase;
    }
    
    public function run($vertabeloXmlFile, $propelSchemaFile, $dbName, 
            $defaultIdMethod, $generateViews) {
        $vertabeloDatabase = simplexml_load_file($vertabeloXmlFile);

        $map = array();
        $propelDatabase = $this->createDatabase($dbName, $defaultIdMethod);
        foreach ($vertabeloDatabase->Tables->Table as $table) {
           $map = $this->createTable($propelDatabase, $table, $map);
        }
        foreach ($vertabeloDatabase->References->Reference as $reference) {
            $map = $this->createForeignKey($reference, $map);
        }
        
        if ($generateViews) {
            foreach ($vertabeloDatabase->Views->View as $view) {
                $map = $this->createView($propelDatabase, $view, $map);
            }
        }

        echo $this->save($propelSchemaFile, $propelDatabase);
    }

    protected function getPropelType($type) {
        $parts = preg_split("/[\(\),]/", strtolower($type), -1, PREG_SPLIT_NO_EMPTY);
        
        var_dump($parts);

        $typeName = $parts[0];
        
        $typeMapping = (new TypeMapping())->getTypeMapping();
        $parts[0] = $typeMapping[$typeName];
        return $parts;
    }

    protected function setColumnType($propelColumn, $columnType){
        // FIXME this doesn't work
        $typeParts = PropelType::fromVertabeloType($columnType);
                //$this->getPropelType($columnType);
        $propelColumn->addAttribute('type', $typeParts->name);

        if ($typeParts->size != NULL) {
            $propelColumn->addAttribute('size', $typeParts->size);
        }
        if ($typeParts->precision != NULL) {
            $propelColumn->addAttribute('scale', $typeParts->precision);
        }
        $propelColumn->addAttribute('sqlType', $columnType);
    }
    
    protected function createColumn($propelTable, $column, $map) {
            $propelColumn = $propelTable->addChild('column');
            $propelColumn->addAttribute('name', $column->Name);

            $this->setColumnType($propelColumn, $column->Type);

            $required = ($column->Nullable == "false") ? "true" : "false";
            $propelColumn->addAttribute('required', $required);

            $primaryKey = ($column->PK == 'true');
            if ($primaryKey) {
                $propelColumn->addAttribute('primaryKey', "true");
            }
            if ($column->Description != '') {
                $propelColumn->addAttribute('description', $column->Description);
            }
            if ($column->DefaultValue != '') {
                $propelColumn->addAttribute('defaultExpr', $column->DefaultValue);
            }
            
            $this->handleColumnAutoincrement($column, $propelColumn);

            $map[(string)$column['Id']] = $propelColumn;
            return $map;
    }
    
    protected function handleColumnAutoincrement($column, $propelColumn) {
        foreach ($column->Properties as $property) {
            $value = $property->Property;
            // MySQL or SQLite
            if ($value->Name == "Auto_increment" || $value == "Autoincrement") {
                $propelColumn->addAttribute("autoIncrement", "true");
            }
        }
        // pgsql
        if ($column->Type == 'serial') {
            $propelColumn->addAttribute("autoIncrement", "true");
        }
    }
    
    protected function createViewColumn($propelTable, $column, $map) {
            $propelColumn = $propelTable->addChild('column');
            $propelColumn->addAttribute('name', $column->Name);

            $this->setColumnType($propelColumn, $column->Type);

            if ($column->Description != '') {
                $propelColumn->addAttribute('description', $column->Description);
            }

            $map[(string)$column['Id']] = $propelColumn;
            return $map;
    }

    protected function createIndex($propelTable, $index, $map) {
        $propelIndex = $propelTable->addChild('index');

        $propelIndex->addAttribute('name', $index->Name);
        $map[(string)$index['Id']] = $propelIndex;

        foreach ($index->Columns as $indexColumn) {
            $propelIndexColumn = $propelIndex->addChild('index-column');

            $indexColumnId = (string)$indexColumn->Column;
            $propelColumn = $map[(string)$indexColumnId];
            $propelIndexColumn -> addAttribute('name', $propelColumn['name']);
        }

        return $map;
    }

    protected function createAlternateKey($propelTable, $alternateKey, $map) {
        $propelUnique = $propelTable->addChild('unique');
        $propelUnique->addAttribute('name', $alternateKey->Name);

        foreach ($alternateKey->Columns->Column as $indexColumnId) {
            $propelIndexColumn = $propelUnique->addChild('unique-column');

            $propelColumn = $map[(string)$indexColumnId];
            $propelIndexColumn -> addAttribute('name', $propelColumn['name']);
        }

        $map[(string)$alternateKey['Id']] = $propelUnique;
        return $map;
    }

    protected function createTable($propelDatabase, $table, $map) {
            $propelTable = $propelDatabase->addChild('table');
            $propelTable->addAttribute('name', $table->Name);
            
            if ($table->Description != '') {
                $propelTable->addAttribute('description', $table->Description);
            }

            foreach ($table->Columns->Column as $column) {
                $map = $this->createColumn($propelTable, $column, $map);
            }

            foreach ($table->Indexes->Index as $index) {
                $map = $this->createIndex($propelTable, $index, $map);
            }

            foreach ($table->AlternateKeys->AlternateKey as $alternateKey) {
                $map = $this->createAlternateKey($propelTable, $alternateKey, $map);
            }

            $map[(string)$table['Id']] = $propelTable;
            return $map;
    }
    
    protected function createView($propelDatabase, $view, $map) {
        $propelTable = $propelDatabase->addChild('table');
        $propelTable->addAttribute('name', $view->Name);

        if ($view->Description != '') {
            $propelTable->addAttribute('description', $view->Description);
        }

        foreach ($view->ViewColumns->ViewColumn as $column) {
            $map = $this->createViewColumn($propelTable, $column, $map);
        }

        $propelTable->addAttribute('readonly', 'true');
        $propelTable->addAttribute('skipSql', 'true');

        $map[(string)$view['Id']] = $propelTable;
        return $map;
    }

    protected function createForeignKey($reference, $map) {
        $fkTableId = (string)$reference->FKTable;
        $pkTableId = (string)$reference->PKTable;
        $fkPropelTable = $map[$fkTableId];
        $pkPropelTable = $map[$pkTableId];

        $propelForeignKey = $fkPropelTable->addChild('foreign-key');
        $propelForeignKey->addAttribute('foreignTable', $pkPropelTable['name']);
        $propelForeignKey->addAttribute('name', $reference->Name);

        foreach ($reference->ReferenceColumns->ReferenceColumn as $referenceColumn) {
            $propelReference = $propelForeignKey->addChild('reference');

            $pkColumnId = (string)$referenceColumn->PKColumn;
            $pkColumn = $map[$pkColumnId];
            $propelReference->addAttribute('foreign', $pkColumn['name']);

            $fkColumnId = (string)$referenceColumn->FKColumn;
            $fkColumn = $map[$fkColumnId];
            $propelReference->addAttribute('local', $fkColumn['name']);
        }
        
        $propelForeignKey->addAttribute('onDelete', $this->getAction('onDelete', $reference->OnDeleteAction, $reference['Id']));
        $propelForeignKey->addAttribute('onUpdate', $this->getAction('onUpdate', $reference->OnUpdateAction, $reference['Id']));

        return $map;
    }
    

    protected function getAction($actionType, $action, $referenceId) {
        if ($action == 'None') {
            return 'none';
        }
        
        if ($action == 'Restrict') {
            return 'restrict';
        }
        
        if ($action == 'SetNull') {
            return 'setnull';
        }
        
        if ($action == 'Cascade') {
            return 'cascade';
        }
        
        if ($action == 'SetDefault') {
            // FIXME: warning
            $this->output->writeln('<comment>'. 
                    'WARNING: reference '. $referenceId
                    . ' ' . $actionType . ' value \'SetDefault\' is not supported by propel, propel value set to \'none\'.'  
                    .'</comment>');
            return 'none';
        }
        
        return null;
    }

    protected function save($file, $xml) {
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $dom->save($file);
    }
    
}
