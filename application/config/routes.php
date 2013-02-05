<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/**
 * API Routes
 */
if ( ! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' ) {

	# Translation
	$route["translation"] = "api/api_translation/index";
	$route["translation/(:num)\.(xml|json)"] = "api/api_translation/index/id/$1";
	$route["translation/(:num)"] = "api/api_translation/index/id/$1";

	#Translations
	$route["translations"] = "api/api_translation/translations";

	#File
	$route["file"] = "api/api_file/index";
	$route["file/(:num)\.(xml|json)"] = "api/api_file/index/id/$1";
	$route["file/(:num)"] = "api/api_file/index/id/$1";

	#File Language Key
	$route["file/(:num)/add/key\.(xml|json)"] = "api/api_file/add_language_key/file_id/$1";
	$route["file/(:num)/add/key"] = "api/api_file/add_language_key/file_id/$1";

	#File with base translations
	$route["project/(:num)/language/(:num)/file/(:num)\.(xml|json)"] = "api/api_file/project_file/project_id/$1/language_id/$2/file_id/$3";
	$route["project/(:num)/language/(:num)/file/(:num)"] = "api/api_file/project_file/project_id/$1/language_id/$2/file_id/$3";

	#Token
	$route["token"] = "api/api_token/index";
	$route["token/(:num)\.(xml|json)"] = "api/api_token/index/id/$1";
	$route["token/(:num)"] = "api/api_token/index/id/$1";

	#Project with language
	$route["project/language/(:num)/(:num)\.(xml|json)"] = "api/api_project/language_project/project/$1/language/$2";
	$route["project/language/(:num)/(:num)"] = "api/api_project/language_project/project/$1/language/$2";

	#Project
	$route["project"] = "api/api_project/index";
	$route["project/(:num)\.(xml|json)"] = "api/api_project/index/id/$1";
	$route["project/(:num)"] = "api/api_project/index/id/$1";

	#Language
	$route["language"] = "api/api_language/index";
	$route["language/(:num)\.(xml|json)"] = "api/api_language/index/id/$1";
	$route["language/(:num)"] = "api/api_language/index/id/$1";

	#Key
	
	#Access Control
	$route["access"] = "api/api_access/index";
	$route["access/(:num)\.(xml|json)"] = "api/api_access/index/id/$1";
	$route["access/(:num)"] = "api/api_access/index/id/$1";

	#Access Control Project
	$route["access/project"] = "api/api_access/project";
	$route["access/project/(:num)\.(xml|json)"] = "api/api_access/project/$1";
	$route["access/project/(:num)"] = "api/api_access/project/$1";

	#Change approval
	$route["change/approval/(:num)/(:num)/(:num)"] = "api/api_translation/approval/language_key_id/$1/language_id/$2/translation_id/$3";

/**
 * User Routes
 */
} else {
	$route["(:any)"] = "front/$1";
	$route["(:any)/(:any)"] = "front/$1/$2";
}

$route['default_controller'] = "front";
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */