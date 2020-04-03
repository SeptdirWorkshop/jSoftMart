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

namespace Joomla\Component\JSoftMart\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\Component\JSoftMart\Administrator\Helper\TranslationHelper;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

class TranslationAdminModel extends AdminModel
{
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @throws  Exception
	 *
	 * @return  CMSObject|boolean  Object on success, false on failure.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			if (property_exists($item, 'translations'))
			{
				if (!is_array($item->translations))
				{
					$item->translations = ArrayHelper::fromObject($item->translations, false);
				}
			}
		}

		return $item;
	}

	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getForm($data = [], $loadData = true)
	{
		return false;
	}

	/**
	 * Method to get the row translation forms array.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  Form[]|boolean  A Form objects array on success, false on failure
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTranslationsForms($data = [], $loadData = true)
	{
		return false;
	}

	/**
	 * Method to get a form object.
	 *
	 * @param   string   $name     The name of the form.
	 * @param   string   $source   The form source. Can be XML string if file flag is set to false.
	 * @param   array    $options  Optional array of options for the form creation.
	 * @param   boolean  $clear    Optional argument to force load a new form.
	 * @param   string   $xpath    An optional xpath to search for the fields.
	 *
	 * @throws  \Exception
	 *
	 * @return  Form[]|false A Form objects array on success, false on failure
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function loadTranslationsForms($name, $source = null, $options = [], $clear = false, $xpath = false)
	{
		// Handle the optional arguments
		$options['control'] = ArrayHelper::getValue((array) $options, 'control', false);

		// Create a signature hash
		$sigoptions = $options;

		if (isset($sigoptions['load_data']))
		{
			unset($sigoptions['load_data']);
		}

		// Initialise forms variables
		$forms         = [];
		$baseSource    = false;
		$baseData      = false;
		$loadFormPaths = false;
		$formFactory   = false;

		foreach (TranslationHelper::getCodes() as $code)
		{
			$formOptions = $options;

			// Handle the optional arguments
			if ($formOptions['control'] = ArrayHelper::getValue((array) $formOptions, 'control', false))
			{
				$formOptions['control'] = $formOptions['control'] . '[translations][' . $code . ']';
			}

			// Create a signature hash. But make sure, that loading the data does not create a new instance
			$sigoptions = $formOptions;
			if (isset($sigoptions['load_data']))
			{
				unset($sigoptions['load_data']);
			}
			$hash = md5($code . $source . serialize($sigoptions));

			// Check if we can use a previously loaded form
			if (!$clear && isset($this->_forms[$hash]))
			{
				$forms[$code] = $this->_forms[$hash];
				continue;
			}

			// Load form paths
			if (!$loadFormPaths)
			{
				Form::addFormPath(JPATH_COMPONENT . '/forms');
			}

			// Get interface
			if (!$formFactory)
			{
				try
				{
					$formFactory = $this->getFormFactory();
				}
				catch (\UnexpectedValueException $e)
				{
					$formFactory = Factory::getContainer()->get(FormFactoryInterface::class);
				}
			}

			// Prepare source
			if (!$baseSource)
			{
				$baseSource = (substr($source, 0, 1) == '<') ? $source : false;
				if (!$baseSource)
				{
					$file = JPATH_COMPONENT . '/forms/translation_' . $source . '.xml';
					if (!File::exists($file))
					{
						throw new \RuntimeException('Form::loadForm could not load file');
					}
					$baseSource = file_get_contents($file);
				}
			}
			$formSource = $baseSource;
			if ($code !== $this->getState('translation.default', 'en-GB'))
			{
				$formSource = str_replace('required="true"', 'required="false"', $baseSource);
			}

			// Create form
			$formName = (!strpos('translation', $name)) ? $name . '.translation' : $name;
			$formName .= '.' . str_replace('-', '_', $code);
			$form     = $formFactory->createForm($formName, $formOptions);
			if ($form->load($formSource, false, $xpath) == false)
			{
				throw new \RuntimeException('Form::loadForm could not load form');
			}

			// Get the data for the form.
			if (isset($options['load_data']) && $options['load_data'])
			{
				if ($baseData === false)
				{
					if ($baseData = $this->loadFormData())
					{
						$baseData = (new Registry($baseData))->toArray();
						$baseData = (isset($baseData['translations'])) ? $baseData['translations'] : [];
					}
				}

				$data = (isset($baseData[$code])) ? $baseData[$code] : [];
			}
			else
			{
				$data = [];
			}

			// Allow for additional modification of the form, and events to be triggered
			$this->preprocessForm($form, $data);

			// Load the data into the form after the plugins have operated
			$form->bind($data);

			// Store the form for later
			$this->_forms[$hash] = $form;

			// Add form to array
			$forms[$code] = $form;
		}

		return (!empty($forms)) ? $forms : false;
	}
}