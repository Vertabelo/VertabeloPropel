VertabeloPropel
===============

Converts [Vertabelo](http://www.vertabelo.com) XML file to [Propel's](http://propelorm.org) schema.xml file.

# Installation

VertabeloPropel uses [Composer] (https://getcomposer.org/) to manage project dependencies.

## Installation via Composer

1. Create a file ``composer.json``:

    ```
    {
       "require": {
          "vertabelo/vertabelo-propel": "*@dev"
       }
    }
    ```

2. Run Composer.
```
composer install
```
This will download VertabeloPropel into the ``vendor`` directory in your project directory.

## Installation via Git
If you don't want to install VertabeloPropel with Composer, you can clone the GitHub repository.

1. Clone the repository.
```
git clone https://github.com/Vertabelo/VertabeloPropel
```
2. Run Composer.

```
cd VertabeloPropel
composer update
```
This will update  VertabeloPropel into the ``vendor`` directory in your project directory.

# Usage

1. Create a database model using [Vertabelo](http://www.vertabelo.com).
2. Download the model as an XML file.
3. Run the vertabelo-propel.php script.
  * If you installed VertabeloPropel via Composer, run
  ```
  vendor/bin/vertabelo-propel.php -i model.xml -o schema.xml --database-name my_db --default-id-method native
  ```
  * If you cloned the GitHub repository, run
  ```
  ./bin/vertabelo-propel.php -i model.xml -o schema.xml --database-name my_db --default-id-method native
  ```
4. Your Propel's schema.xml file is generated. Proceed with Propel as usual.

## Getting help

Run the script without any arguments, to see the list of available options
```
vendor/bin/vertabelo-propel.php 
```
