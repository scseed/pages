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
				'parent' => Jelly::field('BelongsTo', array(
					'foreign' => 'page',
					'column' => 'parent_id'
				)),
				'alias' => Jelly::field('String', array(
					'rules' => array(
						'not_empty' => array(NULL),
					),
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
			));

		parent::initialize($meta);
	}
} // End Model_Page