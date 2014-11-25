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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

use BookQuery;

class DeleteBookCommand extends Command {
    const ID = "id";
    
    protected function configure() {
        $this
            ->setName('books:delete')
            ->setDescription('Deletes a book')
            ->addArgument(DeleteBookCommand::ID, null, InputArgument::REQUIRED, 
                    'Book id');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument(DeleteBookCommand::ID);
        
        $book = BookQuery::create()
                    ->findPk($id);
        
        if ($book == NULL) {
            $output->writeln('No book with id ' . $id);
            return;
        }
        
         BookAuthorQuery::create()
                ->filterByBook($book)
                ->find()
                ->delete();
        $book->delete();
        $output->writeln("Book with id '" . $id . "' successfully deleted.");
    }
}