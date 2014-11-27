VertabeloPropel
===============

Converts [Vertabelo](http://www.vertabelo.com) XML file to [Propel's](http://propelorm.org) schema.xml file.

# Installation

VertabeloPropel uses [Composer] (https://getcomposer.org/) to manage project dependencies.

1. Create a file ``composer.json``:

    ```json
    {
       "require": {
          "vertabelo/vertabelo-propel": "*@dev"
       }
    }
    ```

2. Run Composer.

    ```bash
    composer install
    ```

This will download VertabeloPropel into the ``vendor`` directory in your project directory.

# Usage

1. Create a database model using [Vertabelo](http://www.vertabelo.com).
2. Download the model as an XML file.
3. Run the vertabelo-propel.php script.
  * If you installed VertabeloPropel via Composer, run

        ```bash
	vendor/bin/vertabelo-propel.php -i model.xml -o schema.xml --database-name my_db --default-id-method native
	```

  * If you cloned the GitHub repository, run

        ```bash
	./bin/vertabelo-propel.php -i model.xml -o schema.xml --database-name my_db --default-id-method native
	```

4. Your Propel's schema.xml file is generated. Proceed with Propel as usual.

## Getting help

Run the script without any arguments, to see the list of available options.

```bash
vendor/bin/vertabelo-propel.php 
```

