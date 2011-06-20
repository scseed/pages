<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Pages Core
 *
 * @package Pages
 * @uses Textile
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Controller_Page_Core extends Controller_Template {

	protected $_content_folder = 'frontend/content/';

	/**
	 * Showing static page content by it's alias
	 *
	 * @todo make unification approach to ajax requests (HMVC or pure AJAX)
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$this->_pages_config = Kohana::config('pages');
		$lang   = ($this->_pages_config->multilang_in_structure AND Arr::get($_GET, 'lang', NULL))
			? Arr::get($_GET, 'lang', NULL)
			: I18n::lang();

		$page_contents = $this->_find_page_content($this->_pages_config->multilang_in_structure);

		if( ! count($page_contents))
			throw new HTTP_Exception_404();

		$page = Arr::get($page_contents, $lang, $this->_pages_config->default_language);

		unset($page_contents[$page->lang->abbr]);

		$this->template->title      = $page->title;
		$this->template->page_title = ($page->long_title) ? $page->long_title : $page->title;
		$this->template->content    = View::factory($this->_content_folder . 'page')
			->bind('page_contents', $page_contents)
			->bind('page', $page)
			->bind('multilang_in_structure', $this->_pages_config->multilang_in_structure);
	}

	/**
	 * Looking for page contents.
	 *
	 * @throws HTTP_Exception_404
	 * @param  bool   $multilang_in_structure
	 * @return null|array
	 */
	protected function _find_page_content($multilang_in_structure = FALSE)
	{
		$root_alias      = $this->request->param('page_alias');
	    $subpage_aliases = $this->request->param('subpages');
		$route_lang      = ($multilang_in_structure)
			? $this->request->param('lang')
			: $this->_pages_config->default_language;

		$page_route      = array($route_lang, $root_alias);
		$subpage_aliases = ($subpage_aliases)
			? explode('/', $subpage_aliases)
			: array();

		$page_route = Arr::merge($page_route, $subpage_aliases);

		if( ! $root_alias)
			throw new HTTP_Exception_404('No valid page alias was given');

		$alias_hash = sha1(serialize($page_route));

		$raw_page_contents = Jelly::query('page_content')->get_page_content($alias_hash)->select();

		$page_contents = NULL;

		foreach($raw_page_contents as $page_content)
		{
			$page_contents[$page_content->lang->abbr] = $page_content;
		}

		return $page_contents;
	}

	/**
	 * Getting system languages
	 *
	 * @static
	 * @return array|null|string
	 */
	public static function langs()
	{
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

		return $langs;
	}

} // End Controller_Page_Core