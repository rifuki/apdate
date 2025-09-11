<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	// 'hostname' => 'localhost',
	'hostname' => $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'localhost',
	'port'     => $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 5432,
	'username' => $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'postgres',
	'password' => $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: '',
	'database' => $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'apdate',
	'dbdriver' => 'postgre',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => '',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
