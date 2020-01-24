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

namespace Joomla\Component\JSoftMart\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;
use Joomla\Component\JSoftMart\Administrator\Model\CategoryModel;

class CategoriesController extends AdminController
{
	/**
	 * Rebuild the nested set tree.
	 *
	 * @return  boolean  False on failure or error, true on success.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function rebuild()
	{
		$this->checkToken();

		$this->setRedirect(Route::_('index.php?option=com_jsoftmart&view=categories', false));

		/** @var CategoryModel $model */
		$model = $this->getModel();

		if ($model->rebuild())
		{
			// Rebuild succeeded.
			$this->setMessage(Text::_('COM_JSOFTMART_CATEGORIES_REBUILD_SUCCESS'));

			return true;
		}

		// Rebuild failed.
		$this->setMessage(Text::_('COM_JSOFTMART_CATEGORIES_REBUILD_FAILURE'));

		return false;
	}

	/**
	 * Proxy for getModel
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  The array of possible config values. Optional.
	 *
	 * @return  BaseDatabaseModel  The model.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getModel($name = 'Category', $prefix = 'Administrator', $config = ['ignore_request' => true])
	{
		return parent::getModel($name, $prefix, $config);
	}
}