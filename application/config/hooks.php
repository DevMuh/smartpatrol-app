<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

/*
$hook['pre_system'] = function () {
    $dotenv = Dotenv\Dotenv::createImmutable(FCPATH);
    $dotenv->load();
};
*/
 $hook['pre_system'] = function () {
     $repository = Dotenv\Repository\RepositoryBuilder::createWithNoAdapters()
         ->addAdapter(Dotenv\Repository\Adapter\EnvConstAdapter::class)
         ->addWriter(Dotenv\Repository\Adapter\PutenvAdapter::class)         
	 ->immutable()
         ->make();

     $dotenv = Dotenv\Dotenv::create($repository, FCPATH);
     $dotenv->load();
 };
