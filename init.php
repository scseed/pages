<?php defined('SYSPATH') or die('No direct script access.');

// Load Textile support
require_once Kohana::find_file('vendor', 'textile'.DIRECTORY_SEPARATOR.'textile');

$textile = new Textile();
View::bind_global('textile', $textile);

// Load language conf
$langs = Page::instance()->system_langs();

// Pages route
Route::set('page',	'(<lang>/)<page_path>', array(
		'lang'       => &$langs,
		'page_path' => &$static_pages
	))
	->defaults(array(
		'controller' => 'page',
		'action'     => 'show',
		'lang'       => NULL,
));

// Load top-level pages
$_pages = Page::instance()->pages_structure();

$pages = array();
foreach($_pages as $id => $page)
{
	$pages[] = $page['alias'];
}

$static_pages = ($pages) ? '('.implode('|', $pages).')(.*)' : '(.*)';

// Pages route
Route::set('page',	'(<lang>/)<page_path>', array(
		'lang'       => $langs,
		'page_path' => $static_pages
	))
	->defaults(array(
		'controller' => 'page',
		'action'     => 'show',
		'lang'       => NULL,
));