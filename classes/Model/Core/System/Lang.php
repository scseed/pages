<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * System Languages Jelly Model
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Core_System_Lang extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('system_languages')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'abbr' => Jelly::field('String'),
				'english_name' => Jelly::field('String'),
				'locale_name' => Jelly::field('String'),
				'is_active' => Jelly::field('Boolean'),
			));
	}
} // End Model_System_Lang