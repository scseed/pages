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
	 * @param  string $alias_hash
	 * @return Jelly_Builder
	 */
	public function get_page_content($alias_hash)
	{
		return $this
			->where('page_content:page.full_alias_hash', '=', $alias_hash)
		;
	}

} // End Model_Builder_Page