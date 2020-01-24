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

use Joomla\CMS\Form\Form;

class CategoryModel extends TranslationAdminModel
{
	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @throws \Exception
	 *
	 * @return Form A Form object on success, false on failure
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getForm($data = [], $loadData = true)
	{
		// Get the form
		if ($form = $this->loadForm('com_jsoftmart.category', 'category', ['control' => 'jform', 'load_data' => $loadData]))
		{
			// Modify the form based on access controls
			if (!$this->canEditState((object) $data))
			{
				// Disable fields for display
				$form->setFieldAttribute('state', 'disabled', 'true');

				// Disable fields while saving
				$form->setFieldAttribute('state', 'filter', 'unset');
			}
		}

		return $form;
	}

	/**
	 * Method to get the row translation forms array.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @throws  \Exception
	 *
	 * @return  Form[]|boolean  A Form objects array on success, false on failure
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTranslationForms($data = [], $loadData = true)
	{
		return $this->loadTranslationForm('com_jsoftmart.category', 'category', ['control' => 'jform', 'load_data' => $loadData]);
	}
}