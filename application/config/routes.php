<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'RequireTest';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
// SmartyaACL route
$route['importdatabase'] = 'welcome/importdatabase';
$route['admin'] = 'Admin/index';
$route['admin/login'] = 'AuthAdmin/index';
$route['admin/logout'] = 'AuthAdmin/logout';
$route['login']    = 'Auth/login';
$route['logout']   = 'Auth/logout';
$route['register'] = 'Auth/register';
$route['account']  = 'welcome/account';
//Modules
$route['admin/modules'] = 'Admin/modules';
$route['admin/modules/create'] = 'Admin/module_create';
$route['admin/modules/edit/(:num)'] = 'Admin/module_edit/$1';
$route['admin/modules/delete/(:num)'] = 'Admin/module_delete/$1';
//Roles
$route['admin/roles'] = 'Admin/roles';
$route['admin/roles/create'] = 'Admin/role_create';
$route['admin/roles/edit/(:num)'] = 'Admin/role_edit/$1';
$route['admin/roles/delete/(:num)'] = 'Admin/role_delete/$1';
//Admins
$route['admin/admins'] = 'Admin/admins';
$route['admin/admins/create'] = 'Admin/admin_create';
$route['admin/admins/edit/(:num)'] = 'Admin/admin_edit/$1';
$route['admin/admins/delete/(:num)'] = 'Admin/admin_delete/$1';
//Users
$route['admin/users'] = 'Admin/users';
$route['admin/users/create'] = 'Admin/user_create';
$route['admin/users/edit/(:num)'] = 'Admin/user_edit/$1';
$route['admin/users/delete/(:num)'] = 'Admin/user_delete/$1';
// Custom Routes
$route['/'] = 'general/index';

// Ajax API routes
$route['api'] = 'api/ajax/index';
$route['api/get?(:any)'] = 'api/ajax/get/$1';
$route['api/post'] = 'api/ajax/post';

// Catch all default for direct access to controllers
$route['(:any)/(:any)'] = '$1/$2';
