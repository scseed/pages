<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Page content types Jelly Model
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Page_Type extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('page_types')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'name' => Jelly::field('String'),
				'route_name' => Jelly::field('String', array(
					'default' => 'default'
				)),
				'directory'  => Jelly::field('String', array(
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),
				'controller' => Jelly::field('String', array(
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),
				'action'     => Jelly::field('String', array(
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),
			));
	}
} // End Model_Page_Content_Type