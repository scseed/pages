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
		$page = $this->_find_page_content(I18n::lang());

		if( ! $page->loaded())
			$page = $this->_find_page_content('ru');

		if( ! $page->loaded())
			throw new HTTP_Exception_404();

		$page_view = ($this->_ajax) ? 'home/page' : 'page';

		$this->template->title      = $page->title;
		$this->template->page_title = ($page->long_title) ? $page->long_title : $page->title;
		$this->template->content    = View::factory($this->_content_folder . $page_view)
			->bind('page', $page);
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
		$page_alias      = $this->request->param('page_alias');
	    $subpage_aliases = $this->request->param('subpages');

		if($page_alias === NULL)
			throw new HTTP_Exception_404('No page_alias was given');

		$alias = ($subpage_aliases) ?  $page_alias.'/'.$subpage_aliases : $page_alias;

		$page = Jelly::query('page_content')->get_page_content($lang, $alias)->select();

		return $page;
	}

} // End Controller_Page_Core