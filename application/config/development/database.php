<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_group = 'default';
$query_builder = TRUE;

//'172.25.164.72',
// '42821850', 
// ecsd.cgonm6rex3we.ap-northeast-1.rds.amazonaws.com
$db['default'] = array(
	'dsn'	=> '',
	'hostname' => "192.168.50.104", 
	'username' => 'dcsd',
	'password' => 'jack5899',
	'database' => 'course',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => TRUE,
	'cache_on' => FALSE,
	'cachedir' => '/tmp/db_cache',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);

$db['training'] = array(
    'dsn'    => '',
    'hostname' => '192.168.50.103',
    'username' => 'root',
    'password' => 'jack5899',
    'database' => 'training',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => FALSE,
    'cache_on' => TRUE,
    'cachedir' => '/tmp/db_cache',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);