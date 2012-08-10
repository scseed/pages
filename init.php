<?php defined('SYSPATH') or die('No direct script access.');

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

$multilang = (strlen($langs) > 4);
$pages = array();
foreach($_pages as $id => $page)
{
	if($multilang)
	{
		foreach($page['childrens'] as $id => $subpage)
		{
			$pages[$subpage['alias']] = $subpage['alias'];
		}
	}
	else
	{
		$pages[] = $page['alias'];
	}
}
$static_pages = ($pages) ? '(?!error)('.implode('|', $pages).')(.*)' : '(?!error)(.*)';

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