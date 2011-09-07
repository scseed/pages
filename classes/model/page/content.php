<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Static pages content Jelly Model
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Page_Content extends Jelly_Model {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('page_content')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'page' => Jelly::field('BelongsTo', array(
					'allow_null' => TRUE
				)),
				'lang' => Jelly::field('BelongsTo', array(
					'foreign' => 'system_lang',
					'model' => 'system_lang'
				)),
				'title' => Jelly::field('String'),
				'long_title' => Jelly::field('String'),
				'content' => Jelly::field('Text', array(
						'label' => __('Содержание страницы'),
						'rules' => array(
							array('not_empty'),
						)
					)),
				'is_active' => Jelly::field('Boolean', array(
						'default' => TRUE,
					))
			))
//			->load_with(array(
//				'page',
//				'lang',
//			))
		;
	}
} // End Model_Page_Content