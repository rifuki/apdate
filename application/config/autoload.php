<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

$autoload['libraries'] = array('database','session','template','encryption', 'pagination');

$autoload['drivers'] = array();

$autoload['helper'] = array('url', 'file', 'form', 'app_helper', 'response_helper', 'download');

$autoload['config'] = array();

$autoload['language'] = array();

$autoload['model'] = array('Dbhelper');
