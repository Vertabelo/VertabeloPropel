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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

use BookQuery;

class ListBooksCommand extends Command
{

    const OPTION_SHOW_AUTHORS = "show-authors";
    const OPTION_SHOW_CATEGORY = "show-category";
    const OPTION_TITLE = "title";
    
    protected function configure() {
        $this
            ->setName('books:list')
            ->setDescription('Lists all books')
            ->addOption(ListBooksCommand::OPTION_SHOW_AUTHORS, null, InputOption::VALUE_NONE, 
                    'Show book with author names')
            ->addOption(ListBooksCommand::OPTION_SHOW_CATEGORY, null, InputOption::VALUE_NONE, 
                    'Show book with category')
            ->addOption(ListBooksCommand::OPTION_TITLE, null, InputOption::VALUE_REQUIRED, 
                    'Show matching titles');    
    }
    
    private function shorten($word) {
        if ($word != null) {
            $word = substr($word, 0, 60) . "...";
        }
        return $word;
    }
    
    protected function renderTable(OutputInterface $output, $headers, $rows) {
        $table = new Table($output);
        $table
            ->setHeaders($headers)
            ->setRows($rows);
        $table->render();
    }
    
    private function getAuthors($book) {
        $authors = array();
        $bookAuthors = $book->getBookAuthors();
        foreach ($bookAuthors as $bookAuthor) {
            $authors[] = $bookAuthor->getAuthor()->getName();
        }
        
        return join(", ", $authors);
    }
    
    private function getQuery(InputInterface $input) {
        $bookQuery = BookQuery::create();
        
        if ($input->getOption(ListBooksCommand::OPTION_SHOW_AUTHORS)) {
            $bookQuery = $bookQuery
                    ->leftJoinWith('Book.BookAuthor')
                    ->leftJoinWith('BookAuthor.Author');
        }
        if ($input->getOption(ListBooksCommand::OPTION_SHOW_CATEGORY)) {
            $bookQuery = $bookQuery->joinWith('Book.Category');
        }
        
        if ($input->getOption(ListBooksCommand::OPTION_TITLE) != null) {
            $title = $input->getOption(ListBooksCommand::OPTION_TITLE);
            $bookQuery->filterByTitle('%' . $title . '%');
        }
        
        return $bookQuery;
    }
    
    private function getHeaders(InputInterface $input) {
        $headers = array('Id', 'Title', 'Description');
        
        if ($input->getOption(ListBooksCommand::OPTION_SHOW_AUTHORS)) {
            $headers[] = 'Authors';
        }
        if ($input->getOption(ListBooksCommand::OPTION_SHOW_CATEGORY)) {
            $headers[] = 'Category';
        }
        
        return $headers;
    }
    
    private function getRows(InputInterface $input, $books, $output) {
        $rows = array();
        
        foreach ($books as $book) {
            $description = $this->shorten($book->getDescription());
            
            $row = array($book->getId(), $book->getTitle(), $description);
            
            if ($input->getOption(ListBooksCommand::OPTION_SHOW_AUTHORS)) {
               $authors = $this->getAuthors($book);
               $row[] = $authors;
            }
            
            if ($input->getOption(ListBooksCommand::OPTION_SHOW_CATEGORY)) {
                $category = $book->getCategory();
                $row[] = $category->getName();
            }
            
            $rows[] = $row;
        }
        
        return $rows;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bookQuery = $this->getQuery($input);
        $books = $bookQuery->find();
        
        $rows = $this->getRows($input, $books, $output);
        $headers = $this->getHeaders($input);
        $this->renderTable($output, $headers, $rows);
    }
}