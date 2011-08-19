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

	public static $pages_array = array();
	public static $pages_array_all = array();

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
	 * @param bool        $multiple_roots
	 * @param string|null $lang
	 * @return array|null
	 */
	public function pages_structure($multiple_roots = FALSE, $lang = NULL)
	{
		$type = NULL;
		switch($multiple_roots)
		{
			case FALSE:
				$pages_arr = Arr::get(self::$pages_array, $lang);
				$type = 'pages_array';
				break;
			case TRUE:
				$pages_arr = Arr::get(self::$pages_array_all, $lang);
				$type = 'pages_array_all';
				break;
		}

		if($pages_arr === NULL)
		{
			$lang = ($lang) ? $lang : I18n::lang();
			$default_lang = I18n::lang();
			$pages_content = Jelly::query('page_content')
				->select();

//			exit(Debug::vars($lang) . View::factory('profiler/stats'));
			$content = array();
			foreach($pages_content as $page_content)
			{
				$content[$page_content->page->id][$page_content->lang->abbr] = $page_content;
			}

			$pages_roots = Jelly::query('page')
				->where('parent_page', '=', NULL)
				->where('is_active', '=', FALSE)
				->where('left', '=', 1);

			if( ! $multiple_roots)
			{
				$pages_roots = $pages_roots->where('alias', '=', $lang)->limit(1);
			}

			$pages_roots = $pages_roots->select();

			$pages = array();
			if(! $multiple_roots)
			{
				$pages = $pages_roots->descendants()->as_array();
			}
			else
			{
				foreach($pages_roots as $pages_root_node)
				{
					$pages = array_merge($pages, $pages_root_node->descendants(TRUE)->as_array());
				}
			}

			$pages_array = array();
			$ref         = array();
			foreach($pages as $page )
			{
				$route          = Route::get($page[':type:route_name']);
				$route_defaults = $route->get_defaults();
				$directory  = ($page[':type:directory'])  ? $page[':type:directory']         : Arr::get($route_defaults, 'directory', NULL);
				$controller = ($page[':type:controller']) ? $page[':type:controller']        : $route_defaults['controller'];
				$action     = ($page[':type:action'])     ? $page[':type:action']            : $route_defaults['action'];
				$params     = ($page['params'])     ? $page['params']            : NULL;
				$query      = ($page['query'])      ? $page['query']             : NULL;
				$key        = implode('_', array($page[':type:route_name'], $directory, $controller, $action, $params, $query));

				if($key == 'page__page_show_a:1:{s:9:"page_path";s:4:"home";}_')
					$key = 'default__home_index__';

				$page['key'] = $key;

				$_content = Arr::get($content, $page['id'], NULL);
				$_lang_content = Arr::get($_content, $lang, NULL);
				if( ! $_lang_content)
				{
					$_lang_content = Arr::get($_content, $default_lang, NULL);
				}

				if( ! $_lang_content)
				{
					$_lang_content = Arr::get($_content, $page[':parent_page:alias'], NULL);
				}

				$page['title'] = ($_lang_content) ? $_lang_content->title : $page['alias'];

				if($_lang_content)
				{
					$page['anchor_title'] = ($_lang_content->long_title)
					? $_lang_content->long_title
					: $_lang_content->title;
				}
				else
				{
					$page['anchor_title'] = NULL;
				}

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

			self::${$type}[$lang] = $pages_array;
		}

		return self::${$type}[$lang];
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