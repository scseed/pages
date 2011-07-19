<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Page class instance
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Page_Core {

	/**
	 * Class instance
	 *
	 * @var null|Page
	 */
	public static $instance = NULL;

	/**
	 * System languages container
	 *
	 * @var null|array
	 */
	public static $system_languages = NULL;

	public static $pages_array = NULL;

	/**
	 * Instance initialization
	 *
	 * @static
	 * @return Page
	 */
	public static function instance()
	{
		if( ! is_object(self::$instance))
		{
			self::$instance = new Page;
		}

		return self::$instance;
	}

	/**
	 * Getting system languages for routes
	 *
	 * @return array|null|string
	 */
	public function system_langs()
	{

		if(self::$system_languages == NULL)
		{
			$this->_load_system_languages();
		}


		$langs = NULL;
		foreach(self::$system_languages as $lang)
		{
			$langs[] = (is_object($lang)) ? $lang->abbr : $lang;
		}

		$langs = ($langs) ? '('.implode('|', $langs).')' : NULL;

		return $langs;
	}

	/**
	 * Getting pages structure array
	 * 
	 * @return null|array
	 */
	public function pages_structure()
	{
		if(self::$pages_array === NULL)
		{
			$lang = I18n::lang();
			$pages_content = Jelly::query('page_content')
				->select();

			$content = array();
			foreach($pages_content as $page_content)
			{
				$content[$page_content->page->id][$page_content->lang->abbr] = $page_content;
			}

			$pages_root = Jelly::query('page')
				->where('parent_page', '=', NULL)
				->where('is_active', '=', FALSE)
				->where('left', '=', 1)
				->limit(1)
				->select();

			$pages = $pages_root->descendants()->as_array();

			$pages_array = array();
			$ref         = array();
			foreach($pages as $page )
			{
				$route          = Route::get($page['route_name']);
				$route_defaults = $route->get_defaults();
				$directory  = ($page['directory'])  ? $page['directory']         : Arr::get($route_defaults, 'directory', NULL);
				$controller = ($page['controller']) ? $page['controller']        : $route_defaults['controller'];
				$action     = ($page['action'])     ? $page['action']            : $route_defaults['action'];
				$params     = ($page['params'])     ? $page['params']            : NULL;
				$query      = ($page['query'])      ? $page['query']             : NULL;
				$key        = implode('_', array($page['route_name'], $directory, $controller, $action, $params, $query));

				if($key == 'page__page_show_a:1:{s:9:"page_path";s:4:"home";}_')
					$key = 'default__home_index__';

				$page['key'] = $key;

				$page['title'] = $content[$page['id']][$lang]->title;
				$page['anchor_title'] = $content[$page['id']][$lang]->long_title;

				$page['childrens'] = array();
				if(isset($ref[$page['parent_page']])) // we have a reference on its parent
				{
					$ref[ $page['parent_page'] ]['childrens'][ $page['id'] ] = $page;
					$ref[ $page['id'] ] = &$ref[ $page['parent_page'] ]['childrens'][ $page['id'] ];
				}
				else// we don't have a reference on its parent => put it a root level
				{
					$pages_array[ $page['id'] ] = $page;
					$ref[ $page['id'] ] = &$pages_array[ $page['id'] ];
				}
			}

			unset($ref);

			self::$pages_array = $pages_array;
		}

		return self::$pages_array;
	}

	/**
	 * Getting array of system languages
	 *
	 * @return array|null|string
	 */
	public function system_langs_object()
	{
		if(self::$system_languages == NULL)
		{
			$this->_load_system_languages();
		}

		return self::$system_languages;
	}

	/**
	 * Load system languages
	 *
	 * @return void
	 */
	protected function _load_system_languages()
	{
		$config = Kohana::config('pages');

		if($config->multilanguage === TRUE)
		{
			 $langs = Jelly::query('system_lang')->active()->select();
			foreach($langs as $lang)
			{
				self::$system_languages[] = $lang;
			}
		}
		else
		{
			self::$system_languages = array(i18n::lang());
		}
	}

} // End Controller_page