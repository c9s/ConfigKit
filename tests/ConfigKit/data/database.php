<?php return array (
  'bootstrap' => 
  array (
    0 => 'main.php',
  ),
  'schema' => 
  array (
    'auto_id' => true,
    'loader' => 'phifty/src/Phifty/SchemaLoader.php',
    'base_model' => '\\Phifty\\Model',
    'base_collection' => '\\Phifty\\Collection',
  ),
  'seeds' => 
  array (
    0 => 'InputSystem\\Seed',
    1 => 'User\\Seed',
  ),
  'data_sources' => 
  array (
    'default' => 
    array (
      'dsn' => 'pgsql:host=localhost;dbname=testing',
      'user' => 'root',
      'pass' => 123123,
    ),
  ),
);