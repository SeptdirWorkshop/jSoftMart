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

namespace Joomla\Component\JSoftMart\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Fields\FieldsServiceInterface;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Joomla\Component\Contact\Administrator\Service\HTML\AdministratorService;
use Psr\Container\ContainerInterface;

class JSoftMartComponent extends MVCComponent implements BootableExtensionInterface, FieldsServiceInterface
{
	use HTMLRegistryAwareTrait;

	/**
	 * Booting the extension. This is the function to set up the environment of the extension.
	 *
	 * @param   ContainerInterface  $container  The container.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function boot(ContainerInterface $container)
	{
		$this->getRegistry()->register('jsoftmartadministrator', new AdministratorService);
	}

	/**
	 * Returns a valid section for the given section. If it is not valid then null is returned.
	 *
	 * @param   string  $section  The section to get the mapping for.
	 * @param   object  $item     The item.
	 *
	 * @return  string|null  The new section.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function validateSection($section, $item = null)
	{
		return $section;
	}

	/**
	 * Returns valid contexts.
	 *
	 * @return  array Valid context array.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getContexts(): array
	{
		return array();
	}
}
