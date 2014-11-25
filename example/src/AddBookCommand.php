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

use Book;
use Category;
use Base\CategoryQuery;

class AddBookCommand extends Command
{
    const TITLE = "title";
    const DESCRIPTION = "description";
    const CATEGORY = "category";
    
    protected function configure() {
        $this
            ->setName('books:add')
            ->setDescription('Adds a new book')
            ->addArgument(AddBookCommand::TITLE, 
                    InputArgument::REQUIRED, 'Title of the book')
            ->addArgument(AddBookCommand::DESCRIPTION, 
                    InputArgument::REQUIRED, 'Description of the book')
            ->addArgument(AddBookCommand::CATEGORY, 
                    InputArgument::REQUIRED, 
                    'Book category (will be added if not exists')
           ;
    }
    

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $title = $input->getArgument(AddBookCommand::TITLE);
        $description = $input->getArgument(AddBookCommand::DESCRIPTION);
        $categoryName = $input->getArgument(AddBookCommand::CATEGORY);
        
        $category = CategoryQuery::create()
            ->filterByName($categoryName)
            ->findOne();
        
        if ($category == null) {
            $output->writeln("No category named '" . $categoryName 
                    . "'. The category will be added.");
            
            $category = new Category();
            $category->setName($categoryName);
        }
        
        $book = new Book();
        $book->setTitle($title);
        $book->setDescription($description);
        $book->setCategory($category);
        $book->save();
        
        $output->writeln("Book '" . $title . "' successfully added.");
    }
}
