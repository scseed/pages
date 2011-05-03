<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Template Controller Pages
 *
 * @package Pages
 * @uses Textile
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Controller_Page extends Controller_Template {

	/**
	 * Showing static page content by it's alias
	 *
	 * @throws HTTP_Exception_404
	 * @return void
	 */
	public function action_show()
	{
		$page = $this->_find_page_content();

		if( ! $page->loaded())
			$page = $this->_find_page_content('ru');

		if( ! $page->loaded())
			throw new HTTP_Exception_404();

		$page_view = ($this->_ajax) ? 'home/page' : 'page';

		$this->template->title      = $page->title;
		$this->template->page_title = ($page->long_title) ? $page->long_title : $page->title;
		$this->template->content    = View::factory('frontend/content/' . $page_view)
			->bind('page', $page);
	}

	/**
	 * Looking for page content.
	 *
	 * @throws HTTP_Exception_404
	 * @return Jelly_Model
	 */
	protected function _find_page_content()
	{
		$lang            = I18n::lang();
		$page_alias      = $this->request->param('page_alias');
	    $subpage_aliases = $this->request->param('subpages');

		if($page_alias === NULL)
			throw new HTTP_Exception_404();

		$page = ($subpage_aliases)
			? Jelly::query('page_content')
				->with('lang')
				->with('page')
				->where('page_content:lang.abbr', '=', $lang)
				->where('page_content:page.alias', '=', $page_alias.'/'.$subpage_aliases)
				->limit(1)->select()
			: Jelly::query('page_content')
				->with('lang')
				->with('page')
				->where('page_content:lang.abbr', '=', $lang)
				->where('page_content:page.alias', '=', $page_alias)
				->limit(1)->select();

		return $page;
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

} // End Controller_Page