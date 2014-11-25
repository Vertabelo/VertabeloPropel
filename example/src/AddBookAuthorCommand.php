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

use Author;
use Book;

use BookQuery;
use AuthorQuery;
use BookAuthorQuery;

class AddBookAuthorCommand extends Command
{
    const BOOK_ID = "book_id";
    const AUTHOR_ID = "author_id";
    
    protected function configure() {
        $this
            ->setName('book_author:add')
            ->setDescription('Adds a new book-author association')
            ->addArgument(AddBookAuthorCommand::BOOK_ID, 
                InputArgument::REQUIRED, 'Id of the book')
            ->addArgument(AddBookAuthorCommand::AUTHOR_ID   , 
                InputArgument::REQUIRED, 'Id of the author')
        ;
    }
    
    private function getBookIfExists(OutputInterface $output, $bookId) {
        $book = BookQuery::create()
                    ->findPk($bookId);
        
        if ($book == NULL) {
            $output->writeln('No book with id ' . $bookId);
            exit();
        }
        
        return book;
    }
    
    private function getAuthorIfExists(OutputInterface $output, $authorId) {
        $author = AuthorQuery::create()
                    ->findPk($authorId);
        
        if ($author == NULL) {
            $output->writeln('No author with id ' . $authorId);
            exit();
        }
        
        return book;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bookId = $input->getArgument(AddBookAuthorCommand::BOOK_ID);
        $authorId = $input->getArgument(AddBookAuthorCommand::AUTHOR_ID);

        $book = $this->getBookIfExists($output, $bookId);
        $author = $this->getAuthorIfExists($output, $authorId);
        
        $oldBookAuthor = BookAuthorQuery::create()
                      ->findPk(array($bookId, $authorId));
        if ($oldBookAuthor == NULL) {
            $output->writeln("Book " . $bookId . " - author " . $authorId . "association already exists.");
            return;
        }
        
        $bookAuthor = new BookAuthor();
        $bookAuthor->setAuthor($author);
        $bookAuthor->setBook($book);
        $bookAuthor->save();
        
        $output->writeln("Book " . $bookId . " - author " . $authorId . "association successfully added.");
    }
}