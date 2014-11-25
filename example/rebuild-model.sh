#!/bin/bash

../bin/vertabelo-propel.php --database-name bookshop
vendor/bin/propel model:build
