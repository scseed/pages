<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Load language conf
 */
$langs = Controller_Page::langs();

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
	'subpages'   => '.*'
))
->defaults(array(
	'controller' => 'page',
	'action'     => 'show',
	'lang'       => NULL,
));