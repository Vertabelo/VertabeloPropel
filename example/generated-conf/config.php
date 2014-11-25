<?php
$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('bookshop', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'classname' => 'Propel\\Runtime\\Connection\\ConnectionWrapper',
  'dsn' => 'mysql:host=localhost;dbname=bookshop',
  'user' => 'bookshop',
  'password' => '',
  'attributes' =>
  array (
    'ATTR_EMULATE_PREPARES' => false,
  ),
));
$manager->setName('bookshop');
$serviceContainer->setConnectionManager('bookshop', $manager);
$serviceContainer->setDefaultDatasource('bookshop');