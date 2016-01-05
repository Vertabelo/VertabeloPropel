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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Vertabelo\VertabeloPropel\Vertabelo2PropelConverter;

class VertabeloPropelCommand extends Command
{
    const OPTION_VERTABELO_XML = "input-file";
    const OPTION_SCHEMA_XML = "output-file";
    const OPTION_DB_NAME = "database-name";
    const OPTION_DEFAULT_ID_METHOD = "default-id-method";
    const OPTION_VIEWS = "views";
    
    protected function configure() {
        $this
            ->setName('vertabelo:generate-propel')
            ->setDescription('Generate Propel schema.xml from Vertabelo XML file')
            ->addOption(
                VertabeloPropelCommand::OPTION_VERTABELO_XML,
                'i',    
                InputOption::VALUE_REQUIRED,
                'Location of Vertabelo XML file',
                'model.xml')
            ->addOption(
                VertabeloPropelCommand::OPTION_SCHEMA_XML,
                'o',
                InputOption::VALUE_REQUIRED,
                'Location of output Propel schema.xml file',
                'schema.xml')
            ->addOption(VertabeloPropelCommand::OPTION_DB_NAME, 
                null, 
                InputOption::VALUE_REQUIRED, 
                'Name of the database',
                'test')    
            ->addOption(VertabeloPropelCommand::OPTION_DEFAULT_ID_METHOD, 
                null, 
                InputOption::VALUE_OPTIONAL, 
                'Database\'s defaultIdMethod',
                'native')
            ->addOption(VertabeloPropelCommand::OPTION_VIEWS, 
                null, 
                InputOption::VALUE_NONE, 
                'Generates table declaration for views')    
            ;
    }
    
    function validateArguments(OutputInterface $output, $inputFile, $outputFile, $defaultIdMethod) {
        if (!file_exists($inputFile)) {
            $output->writeln('<error>File ' . $inputFile . ' does not exist</error>');
            exit(1);
        }
        
        if (! is_readable($inputFile)) {
            $output->writeln('<error>Cannot read from file ' . $inputFile . '</error>');
            exit(1);
        }
        
        if (! is_writable($inputFile)) {
            $output->writeln('<error>Cannot write to file ' . $inputFile . '</error>');
            exit(1);
        }
        
        if (! ($defaultIdMethod == 'native' || $defaultIdMethod == 'none')) {
            $output->writeln('<error>Invalid value defaultIdMethod ' . $defaultIdMethod 
                    . '. Allowed values are \'native\' or \'none\'.</error>');
            exit(1);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $inputFile = $input->getOption(VertabeloPropelCommand::OPTION_VERTABELO_XML);
        $outputFile = $input->getOption(VertabeloPropelCommand::OPTION_SCHEMA_XML);
        $defaultIdMethod = $input->getOption(VertabeloPropelCommand::OPTION_DEFAULT_ID_METHOD);
        $dbName = $input->getOption(VertabeloPropelCommand::OPTION_DB_NAME);

        $this->validateArguments($output, $inputFile, $outputFile, $defaultIdMethod);
        
        $output->writeln('Generating Propel schema.xml ...');

        $vertabeloDatabase = simplexml_load_file($inputFile);

        $version = (string)$vertabeloDatabase['VersionId'];


        if ($version  == "2.2" || $version  == "2.1") {
            $converter = new Vertabelo2PropelConverter_v2_2($output);
        } else if ($version == "2.3") {
            $converter = new Vertabelo2PropelConverter_v2_3($output);
        } else {
            $output->writeln('ERROR: Vertabelo XML format version "' . $version .  '" is not supported');
            return;
        }

        $converter->run(
            $vertabeloDatabase,
            $outputFile,
            $dbName,
            $defaultIdMethod,
            $input->getOption(VertabeloPropelCommand::OPTION_VIEWS)
        );
        
        $output->writeln('Propel schema saved in ' . $outputFile);
    }
}