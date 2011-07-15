<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Page Model Jelly Builder
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Builder_Page_Content extends Jelly_Builder {

	/**
	 * Gets page content based on $lang and $alias
	 *
	 * @param  string $lang
	 * @param  string $page_id
	 * @return Jelly_Builder
	 */
	public function get_page_content($lang, $page_id)
	{
		return $this
			->where('page_content:lang.abbr', '=', $lang)
			->where('page_content:page.id', '=', $page_id)
			->limit(1);
	}

} // End Model_Builder_Page