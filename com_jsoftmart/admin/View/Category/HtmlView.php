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

namespace Joomla\Component\JSoftMart\Administrator\View\Category;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
	/**
	 * The Form object.
	 *
	 * @var  Form
	 *
	 * @since __DEPLOY_VERSION__
	 */
	protected $form;

	/**
	 * The active item.
	 *
	 * @var  object
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $item;

	/**
	 * The model state.
	 *
	 * @var  CMSObject
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $state;

	/**
	 * The Translation Forms forms array.
	 *
	 * @var  Form[]
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $translationForms;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @throws \Exception
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function display($tpl = null)
	{
		// Initialise variables
		$this->form             = $this->get('Form');
		$this->item             = $this->get('Item');
		$this->state            = $this->get('State');
		$this->translationForms = $this->get('translationForms');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode('\n', $errors), 500);
		}

		// Add title and toolbar
		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		$isNew = ($this->item->id == 0);
		$canDo = ContentHelper::getActions('com_jsoftmart', 'category');

		// Disable menu
		Factory::getApplication()->input->set('hidemainmenu', true);

		// Set page title
		$title = ($isNew) ? Text::_('COM_JSOFTMART_CATEGORY_ADD') : Text::_('COM_JSOFTMART_CATEGORY_EDIT');
		ToolbarHelper::title(Text::_('COM_JSOFTMART') . ': ' . $title, 'cart');

		// Build the actions for new and existing records
		if ($isNew && $canDo->get('core.create'))
		{
			ToolbarHelper::apply('category.apply');
			ToolbarHelper::saveGroup(
				[
					['save', 'category.save'],
					['save2new', 'category.save2new']
				],
				'btn-success'
			);
		}
		elseif (!$isNew && ($canDo->get('core.edit')
				|| ($canDo->get('core.edit.own') && $this->item->created_by == Factory::getUser()->id)))
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner
			ToolbarHelper::apply('category.apply');
			$toolbarButtons = [['save', 'category.save']];

			// We can save this record, but check the create permission to see if we can return to make a new one
			if ($canDo->get('core.create'))
			{
				$toolbarButtons[] = ['save2new', 'category.save2new'];
			}

			ToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);
		}

		ToolbarHelper::cancel('category.cancel');
	}
}
