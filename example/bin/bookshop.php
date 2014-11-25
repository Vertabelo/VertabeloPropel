#!/usr/bin/env php

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

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../generated-conf/config.php';

use Symfony\Component\Console\Application;
use Vertabelo\VertabeloPropel\Example\ListBooksCommand;
use Vertabelo\VertabeloPropel\Example\AddBookCommand;
use Vertabelo\VertabeloPropel\Example\AddAuthorCommand;
use Vertabelo\VertabeloPropel\Example\ListAuthorsCommand;
use Vertabelo\VertabeloPropel\Example\DeleteAuthorCommand;
use Vertabelo\VertabeloPropel\Example\DeleteBookCommand;
use Vertabelo\VertabeloPropel\Example\AddBookAuthorCommand;
use Vertabelo\VertabeloPropel\Example\EditAuthorCommand;

$application = new Application();
$application->add(new ListBooksCommand());
$application->add(new AddBookCommand());
$application->add(new AddAuthorCommand());
$application->add(new ListAuthorsCommand());
$application->add(new DeleteAuthorCommand());
$application->add(new DeleteBookCommand());
$application->add(new AddBookAuthorCommand());
$application->add(new EditAuthorCommand());
$application->run();

