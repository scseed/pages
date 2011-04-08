<?php defined('SYSPATH') or die('No direct script access.');

$config = Kohana::config('pages');

$langs = i18n::lang();
if($config->multilanguage === TRUE)
{
	// Load language conf
	$_langs = Jelly::query('system_lang')->active()->select();
	$langs = array();
	foreach($_langs as $lang)
	{
		$langs[] = $lang->abbr;
	}

	$langs = ($langs) ? '('.implode('|', $langs).')' : NULL;
}

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
	'subpages'   => '.+'
))
->defaults(array(
	'controller' => 'page',
	'action'     => 'show',
	'lang'       => NULL,
));