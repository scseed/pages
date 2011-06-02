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
	 * @param  string $alias
	 * @return Jelly_Builder
	 */
	public function get_page_content($lang, $alias)
	{
		return $this
			->where('page_content:lang.abbr', '=', $lang)
			->where('page_content:page.alias', '=', $alias)
			->limit(1);
	}

} // End Model_Builder_Page