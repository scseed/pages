<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Static page Jelly Model
 *
 * @package Pages
 * @uses Jelly_Model_MPTT
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
class Model_Page extends Jelly_Model_MPTT {

	/**
	 * Initializating model meta information
	 *
	 * @param Jelly_Meta $meta
	 */
	public static function initialize(Jelly_Meta $meta)
	{
		$meta->table('pages')
			->name_key('alias')
			->fields(array(
				'id' => Jelly::field('Primary'),
				'parent_page' => Jelly::field('BelongsTo', array(
					'foreign'       => 'page',
					'column'        => 'parent_id',
					'model'         => 'page',
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),
				'alias' => Jelly::field('String', array(
					'default' => NULL,
					'allow_null' => TRUE,
					'convert_empty' => TRUE,
				)),
				'date_create' => Jelly::field('Timestamp', array(
					'default' => time(),
				)),
				'is_active' => Jelly::field('Boolean', array(
					'default' => TRUE,
					'label' => 'Опубликовано',
					'label_true' => 'да',
					'label_false' => 'нет'
				)),
				'page_contents' => Jelly::field('HasMany'),
			))
			->load_with(array('parent_page'))
		;

		parent::initialize($meta);
	}
} // End Model_Page