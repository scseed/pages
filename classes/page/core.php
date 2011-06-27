<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Page class instance
 *
 * @package Pages
 * @author Sergei Gladkovskiy <smgladkovskiy@gmail.com>
 */
abstract class Page_Core {

	/**
	 * Class instance
	 *
	 * @var null|Page
	 */
	public static $instance = NULL;

	/**
	 * System languages container
	 *
	 * @var null|array
	 */
	public static $system_languages = NULL;

	/**
	 * Instance initialization
	 *
	 * @static
	 * @return Page
	 */
	public static function instance()
	{
		if( ! is_object(self::$instance))
		{
			self::$instance = new Page;
		}

		return self::$instance;
	}

	/**
	 * Getting system languages for routes
	 *
	 * @return array|null|string
	 */
	public function system_langs()
	{

		if(self::$system_languages == NULL)
		{
			$this->_load_system_languages();
		}


		$langs = NULL;
		foreach(self::$system_languages as $lang)
		{
			$langs[] = (is_object($lang)) ? $lang->abbr : $lang;
		}

		$langs = ($langs) ? '('.implode('|', $langs).')' : NULL;

		return $langs;
	}

	/**
	 * Getting array of system languages
	 *
	 * @return array|null|string
	 */
	public function system_langs_object()
	{
		if(self::$system_languages == NULL)
		{
			$this->_load_system_languages();
		}

		return self::$system_languages;
	}

	/**
	 * Load system languages
	 *
	 * @return void
	 */
	protected function _load_system_languages()
	{
		$config = Kohana::config('pages');

		if($config->multilanguage === TRUE)
		{
			 $langs = Jelly::query('system_lang')->active()->select();
			foreach($langs as $lang)
			{
				self::$system_languages[] = $lang;
			}
		}
		else
		{
			self::$system_languages = array(i18n::lang());
		}
	}

} // End Controller_page