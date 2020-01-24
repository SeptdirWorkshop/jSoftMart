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

namespace Joomla\Component\JSoftMart\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\Database\DatabaseDriver;

class CategoryTable extends TranslationNestedTable
{
	/**
	 * Constructor.
	 *
	 * @param   DatabaseDriver  $db  Database driver object.
	 *
	 * @throws  \Exception
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(DatabaseDriver $db)
	{
		parent::__construct('#__jsoftmart_categories', 'id', $db);

		// Set the alias since the column is called state
		$this->setColumnAlias('published', 'state');
	}
}