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

namespace Joomla\Component\JSoftMart\Administrator\View\Categories;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Pagination\Pagination;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $items;

	/**
	 * The pagination object.
	 *
	 * @var  Pagination
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $pagination;

	/**
	 * The model state.
	 *
	 * @var  CMSObject
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $state;

	/**
	 * Form object for search filters.
	 *
	 * @var  Form
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $filterForm;

	/**
	 * The active search filters.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $activeFilters;

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
		$this->state         = $this->get('State');
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Add title and toolbar
		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function addToolbar()
	{
		$canDo   = ContentHelper::getActions('com_jsoftmart', 'category');
		$toolbar = Toolbar::getInstance('toolbar');

		// Set page title
		ToolbarHelper::title(Text::_('COM_JSOFTMART') . ': ' . Text::_('COM_JSOFTMART_CATEGORIES'), 'cart');

		// Add create button
		if ($canDo->get('core.create'))
		{
			$toolbar->addNew('category.add');
		}

		// Add actions toolbar
		if ($canDo->get('core.edit.state') || Factory::getUser()->authorise('core.admin'))
		{
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('fa fa-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);
			$childBar = $dropdown->getChildToolbar();

			// Add publish & unpublish
			if ($canDo->get('core.edit.state'))
			{
				$childBar->publish('categories.publish')->listCheck(true);
				$childBar->unpublish('categories.unpublish')->listCheck(true);
			}

			// Add trash
			if ($canDo->get('core.edit.state') && (int) $this->state->get('filter.published') !== -2)
			{
				$childBar->trash('categories.trash')->listCheck(true);
			}
		}

		// Add delete button
		if ((int) $this->state->get('filter.published') === -2 && $canDo->get('core.delete'))
		{
			$toolbar->delete('categories.delete');
		}

		// Add rebuild button
		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			$toolbar->standardButton('refresh')
				->text('JTOOLBAR_REBUILD')
				->task('categories.rebuild');
		}

		// Add preferences button
		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			$toolbar->preferences('com_jsoftmart');
		}
	}
}
