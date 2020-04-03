<?php
/**
 * @package     jSoftMart Package
 * @subpackage  com_jsoftmart
 * @version     __DEPLOY_VERSION__
 * @author      Septdir Workshop - septdir.com
 * @copyright   Copyright (c) 2018 - 2019 Septdir Workshop. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://www.septdir.com/
 */

namespace Joomla\Component\JSoftMart\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Multilanguage;

class TranslationHelper
{
	/**
	 * Translations data.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $_translations = null;

	/**
	 * Translations codes.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $_codes = null;

	/**
	 * Default translation code.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $_default = null;

	/**
	 * Current translation code.
	 *
	 * @var  string
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected static $_current = null;

	/**
	 * Method for getting translations.
	 *
	 * @return  object[]  Translations data array.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getTranslations()
	{
		if (self::$_translations === null)
		{
			$languages = LanguageHelper::getInstalledLanguages(0, true);
			$default   = ComponentHelper::getParams('com_languages')->get('site', 'en-GB');
			$current   = Factory::getLanguage()->getTag();
			$multilang = Multilanguage::isEnabled();

			$translations = [];
			$first        = [];
			foreach ($languages as $code => $language)
			{
				if (!$multilang && $code !== $default) continue;

				$translation          = new \stdClass;
				$translation->name    = $language->metadata['name'];
				$translation->code    = $code;
				$translation->default = ($code === $default) ? 1 : 0;
				$translation->current = ($code === $current) ? 1 : 0;
				$translation->image   = strtolower(str_replace('-', '_', $code));

				if ($translation->current)
				{
					self::$_current = $code;
				}
				if ($translation->default)
				{
					$first[$code]   = $translation;
					self::$_default = $code;
				}
				else
				{
					$translations[$code] = $translation;
				}
			}

			$translations = $first + $translations;

			self::$_translations = $translations;
			self::$_codes        = array_keys($translations);
		}

		return self::$_translations;
	}

	/**
	 * Method for getting translations codes.
	 *
	 * @return  array  Translations codes.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getCodes()
	{
		if (self::$_codes === null)
		{
			self::getTranslations();
		}

		return self::$_codes;
	}

	/**
	 * Method for getting default translation code.
	 *
	 * @return  string Default translation code.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getDefault()
	{
		if (self::$_default === null)
		{
			self::getTranslations();
		}

		return self::$_default;
	}

	/**
	 * Method for getting current translation code.
	 *
	 * @return  string Default translation code.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function getCurrent()
	{
		if (self::$_current === null)
		{
			self::getTranslations();
		}

		return self::$_current;
	}

	/**
	 * Method for getting is current translation default.
	 *
	 * @return  bool Default translation code.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function isDefault()
	{
		if (self::$_translations === null)
		{
			self::getTranslations();
		}

		return self::$_default === self::$_current;
	}
}