<?php defined('SYSPATH') or die('No direct script access.');

// Load Textile support
require_once Kohana::find_file('vendor', 'textile'.DIRECTORY_SEPARATOR.'textile');

$textile = new Textile();
View::bind_global('textile', $textile);

// Load language conf
$langs = Page::instance()->system_langs();

// Load top-level pages
$_pages = Jelly::query('page')->get_grand_pages()->select();

$pages = array();
foreach($_pages as $page)
{
	$pages[] = $page->alias;
}

$static_pages = ($pages) ? '('.implode('|', $pages).')' : '';

// Pages route
Route::set('page',	'(<lang>)(/)<page_alias>(/<subpages>)', array(
		'lang'       => $langs,
		'page_alias' => $static_pages,
		'subpages'   => '[\w/-]+'
	))
	->defaults(array(
		'controller' => 'page',
		'action'     => 'show',
		'lang'       => NULL,
));