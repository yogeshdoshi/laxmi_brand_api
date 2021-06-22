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
|	http://codeigniter.com/user_guide/general/routing.html
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

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['category'] = 'CategoryController';
$route['admin/product/list'] = 'api/admin/ProductController/get_product';
$route['admin/product/list/(:num)'] = 'api/admin/ProductController/get_product/$1';
$route['admin/product/detail'] = 'api/admin/ProductController/product_detail';
$route['admin/product/delete'] = 'api/admin/ProductController/delete';
$route['admin/product/save'] = 'api/admin/ProductController/insert_product';
$route['admin/product/update'] = 'api/admin/ProductController/update_product';

// category
$route['admin/category/list'] = 'api/admin/CategoryController/get_category';
$route['admin/category/list/(:num)'] = 'api/admin/CategoryController/get_category/$1';
$route['admin/category/insert'] = 'api/admin/CategoryController/insert_category';
$route['admin/category/update'] = 'api/admin/CategoryController/update_category';
$route['admin/category/delete'] = 'api/admin/CategoryController/delete_category';


// faq
$route['admin/faq/list'] = 'api/admin/FaqController/get_faq';
$route['admin/faq/list/(:num)'] = 'api/admin/FaqController/get_faq/$1';
$route['admin/faq/insert'] = 'api/admin/FaqController/insert_faq';
$route['admin/faq/update'] = 'api/admin/FaqController/update_faq';
$route['admin/faq/delete'] = 'api/admin/FaqController/delete_faq';
$route['admin/faq/detail'] = 'api/admin/FaqController/faq_detail';

// offer
$route['admin/offer/list'] = 'api/admin/OfferController/get_offer';
$route['admin/offer/list/(:num)'] = 'api/admin/OfferController/get_offer/$1';
$route['admin/offer/insert'] = 'api/admin/OfferController/insert_offer';
$route['admin/offer/update'] = 'api/admin/OfferController/update_offer';
$route['admin/offer/delete'] = 'api/admin/OfferController/delete_offer';
$route['admin/offer/detail'] = 'api/admin/OfferController/offer_detail';

// admin
$route['admin/login'] = 'api/admin/LoginController/login_post';
$route['admin/pal'] = 'api/admin/LoginController/pal';
$route['admin/fileupload'] = 'api/admin/LoginController/_fileupload';


$route['admin/upload'] = 'api/admin/LoginController/_uploadimg';


$route['admin/upload/images'] = 'api/admin/Uploadimages/index';


// ProductController
