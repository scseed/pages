<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Page content types Jelly Model
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Page_Content_Type extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('page_content_types')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'name' => Jelly::field('String'),
			));
	}
} // End Model_Page_Content_Type