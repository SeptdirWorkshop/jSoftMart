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

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\Database\DatabaseQuery;
use Joomla\Database\ParameterType;
use Joomla\Database\QueryInterface;

class CategoriesModel extends ListModel
{
	/**
	 * The default site language.
	 *
	 * @var  array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $translation = null;

	/**
	 * Constructor.
	 *
	 * @param   array                $config   An optional associative array of configuration settings.
	 * @param   MVCFactoryInterface  $factory  The factory.
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null)
	{
		// Set translation
		$this->translation = ComponentHelper::getParams('com_languages')->get('site', 'en-GB');

		// Add the ordering filtering fields whitelist
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = [
				'id', 'c.id',
				'title', 'c.title',
				'published', 'state', 'c.state',
				'ordering', 'lft', 'c.lft',
			];
		}

		parent::__construct($config, $factory);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @throws  \Exception
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// Set search filter state
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// Set published filter state
		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		// List state information
		$ordering  = empty($ordering) ? 'c.lft' : $ordering;
		$direction = empty($direction) ? 'asc' : $direction;

		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get a DatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  DatabaseQuery|QueryInterface  A DatabaseQuery object to retrieve the data set.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'c.id, c.alias, c.state,  c.path, c.parent_id, c.level, c.lft, c.rgt'
			)
		)
			->from($db->quoteName('#__jsoftmart_categories', 'c'))
			->where($db->quoteName('c.alias') . ' <> ' . $db->quote('root'));

		// Join over translations
		$translation = $this->translation;
		$query->select([
			$db->quoteName('t_c.title', 'title')
		])
			->leftJoin($db->quoteName('#__jsoftmart_categories_translations', 'ct')
				. ' ON ct.id = c.id AND ' . $db->quoteName('ct.language') . ' = ' . $db->quote($translation));

		// Filter by published state
		$published = (string) $this->getState('filter.published');
		if (is_numeric($published))
		{
			$published = (int) $published;
			$query->where($db->quoteName('c.published') . ' = :published')
				->bind(':published', $published, ParameterType::INTEGER);
		}
		elseif ($published === '')
		{
			$query->whereIn($db->quoteName('c.published'), [0, 1]);
		}

		// Filter by search
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				if (stripos($search, 'id:') === 0)
				{
					$search = (int) substr($search, 3);
					$query->where($db->quoteName('c.id') . ' = :search')
						->bind(':search', $search, ParameterType::INTEGER);
				}
			}
			else
			{
				$query->leftJoin($db->quoteName('#__jsoftmart_categories_translations', 'ta_c') . ' ON cta.id = c.id');
				$search = '%' . str_replace(' ', '%', trim($search)) . '%';
				$query->extendWhere(
					'AND',
					[
						$db->quoteName('c.alias') . ' LIKE :alias',
						$db->quoteName('cta.title') . ' LIKE :title',
						$db->quoteName('cta.introtext') . ' LIKE :introtext',
						$db->quoteName('cta.fulltext') . ' LIKE :fulltext',
					],
					'OR'
				)
					->bind(':alias', $search)
					->bind(':title', $search)
					->bind(':introtext', $search)
					->bind(':fulltext', $search);
			}
		}

		// Group by
		$query->group('c.id');

		// Add the list ordering clause
		$ordering  = $this->state->get('list.ordering', 'c.lft');
		$direction = $this->state->get('list.direction', 'asc');
		$query->order($db->escape($ordering) . ' ' . $db->escape($direction));

		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function getItems()
	{
		if ($items = parent::getItems())
		{
			foreach ($items as &$item)
			{
				// Set title
				$item->title = (empty($item->title)) ? $item->alias : $item->title;
			}
		}

		return $items;
	}
}