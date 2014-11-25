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

namespace Vertabelo\VertabeloPropel\Example;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use AuthorQuery;

class ListAuthorsCommand extends Command
{
    protected function configure() {
        $this
            ->setName('authors:list')
            ->setDescription('Lists all authors');
    }
    
    protected function renderTable(OutputInterface $output, $headers, $rows) {
        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($rows);
        $table->render();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $authorQuery = AuthorQuery::create();
        $authors = $authorQuery->find();
        
        $rows = array();
        foreach ($authors as $author) {
            $rows[] = array($author->getId(), $author->getName());
        }
        
        $this->renderTable($output, array('Id', 'Name'), $rows);
    }
}