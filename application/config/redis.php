<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['socket_type'] = 'tcp'; //`tcp` or `unix`
//$config['socket'] = '/var/run/redis.sock'; // in case of `unix` socket type
$config['host'] = '192.168.50.105'; //change this to match your amazon redis cluster node endpoint
$config['password'] = NULL;
$config['port'] = 6379;
$config['timeout'] = 0;