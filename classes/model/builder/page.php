<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Page Model Jelly Builder
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Builder_Page extends Jelly_Builder {

	/**
	 * Looking for page by it's alias
	 *
	 * @throws HTTP_Exception_404
	 * @param  string|null $page_alias
	 * @return Jelly_Builder
	 */
	public function get_page($page_alias = NULL)
	{
		if($page_alias === NULL)
			throw new HTTP_Exception_404();

		return $this
			->where('is_active', '=', TRUE)
			->where('alias', '=', Html::chars($page_alias))
			->limit(1);
	}

	/**
	 * Grand pages selection
	 *
	 * @return Jelly_Builder
	 */
	public function get_grand_pages()
	{
		return $this
			->active()
			->where('parent', '=', 1);
	}

} // End Model_Builder_Page