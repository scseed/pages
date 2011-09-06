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

	public function before()
	{
		$this->_config = Kohana::config('pages');
		View::set_global('multilang_in_structure', $this->_config->multilanguage_in_structure);
		parent::before();
	}

	/**
	 * Showing static page content by it's alias
	 *
	 * @todo make unification approach to ajax requests (HMVC or pure AJAX)
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$lang_in_get  = Arr::get($_GET, 'lang', NULL);
		$lang         = ($lang_in_get) ? $lang_in_get : I18n::lang();

		$page_content = $this->_find_page_content($lang);

		if( ! $page_content->loaded() AND $this->_config->multilanguage_in_structure === FALSE)
			$page_content = $this->_find_page_content('ru');

		if( ! $page_content->loaded())
			throw new HTTP_Exception_404();

		$page_view = ($this->_ajax) ? 'home/page' : 'page';

		$other_page_languages = Jelly::query('page_content')
			->where('page', '=', $page_content->page->id)
			->where('lang', '!=', $page_content->lang->id)
			->select();

		$this->template->title      = $page_content->title;
		$this->template->page_title = ($page_content->long_title) ? $page_content->long_title : $page_content->title;
		$this->template->content    = View::factory($this->_content_folder . $page_view)
			->bind('page', $page_content)
			->bind('other_page_languages', $other_page_languages);
	}

	/**
	 * Looking for page content.
	 *
	 * @throws HTTP_Exception_404
	 * @param string $lang
	 * @return Jelly_Model
	 */
	protected function _find_page_content($lang)
	{
		$page_path = HTML::chars($this->request->param('page_path'));

		$page_aliases = explode('/', $page_path);

		$pages_array = Page::instance()->pages_structure(FALSE, $lang);

		$current_page = $this->_find_current_page($pages_array, $page_aliases);

		$current_page = Jelly::query('page_content')
			->get_page_content($lang, $current_page['id'])
			->select();

		return $current_page;
	}

	/**
	 * Finds page id based on page path array
	 *
	 * @recursive
	 * @throws HTTP_Exception_404
	 * @param $pages_array
	 * @param $page_alias
	 * @return null
	 */
	protected function _find_current_page($pages_array, $page_alias)
	{
		$alias_path = array_shift($page_alias);

		if($alias_path !== NULL)
		{
			$current_page = NULL;

			foreach($pages_array as $id => $page)
			{
				if($page['alias'] == $alias_path)
				{
					$current_page = $page;
					break;
				}
			}

			if($current_page !== NULL AND $current_page['childrens'] AND $page_alias)
			{
				$current_page = $this->_find_current_page($current_page['childrens'], $page_alias);
			}
			elseif($current_page !== NULL AND $page_alias)
			{
				throw new HTTP_Exception_404('Page is not found');
			}
		}

		return $current_page;
	}

} // End Controller_Page_Core