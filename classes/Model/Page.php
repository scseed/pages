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
				'type'        => Jelly::field('BelongsTo', array(
					'foreign' => 'page_type',
					'model'   => 'page_type'
				)),
				'creator'     => Jelly::field('BelongsTo', array(
					'foreign'       => 'user',
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),
				'updater'     => Jelly::field('BelongsTo', array(
					'foreign'       => 'user',
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),

				'page_contents' => Jelly::field('HasMany'),

				'alias'      => Jelly::field('String', array(
					'label' => __('Алиас'),
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
					'rules' => array(
						array('alpha_dash')
					)
				)),
				'params'     => Jelly::field('Serialized', array(
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),
				'query'      => Jelly::field('String', array(
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),
				'class'      => Jelly::field('String', array(
					'default'       => NULL,
					'allow_null'    => TRUE,
					'convert_empty' => TRUE,
				)),

				'date_create' => Jelly::field('Timestamp', array(
					'auto_now_create' => TRUE,
				)),
				'date_update' => Jelly::field('Timestamp', array(
					'auto_now_update' => TRUE,
				)),

				'is_visible'   => Jelly::field('Boolean', array(
					'default'     => TRUE,
					'true_label'  => __('виден'),
					'false_label' => __('не виден'),
				)),
				'is_active' => Jelly::field('Boolean', array(
					'default'     => TRUE,
					'label'       => 'Опубликовано',
					'label_true'  => 'да',
					'label_false' => 'нет'
				)),
			))
			->load_with(array(
				'parent_page',
				'type',
//				'creator',
//				'updater',
			))
		;

		parent::initialize($meta);
	}
} // End Model_Page