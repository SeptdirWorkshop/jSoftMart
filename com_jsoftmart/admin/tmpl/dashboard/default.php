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

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.core');

// Load JavaScript message titles
Text::script('ERROR');
Text::script('WARNING');
Text::script('NOTICE');
Text::script('MESSAGE');
Text::script('COM_CPANEL_UNPUBLISH_MODULE_SUCCESS');
Text::script('COM_CPANEL_UNPUBLISH_MODULE_ERROR');

// Load scripts
HTMLHelper::script('com_cpanel/admin-cpanel-default.min.js', array('version' => 'auto', 'relative' => true));
HTMLHelper::script('com_cpanel/admin-add_module.js', ['version' => 'auto', 'relative' => true]);

// Set up the bootstrap modal that will be used for all module editors
echo HTMLHelper::_(
	'bootstrap.renderModal',
	'moduleDashboardAddModal',
	array(
		'title'      => Text::_('COM_CPANEL_ADD_MODULE_MODAL_TITLE'),
		'backdrop'   => 'static',
		'url'        => Route::_('index.php?option=com_cpanel&task=addModule&function=jSelectModuleType&position=' . $this->escape($this->position)),
		'bodyHeight' => '70',
		'modalWidth' => '80',
		'footer'     => '<button type="button" class="button-cancel btn btn-sm btn-danger" data-dismiss="modal" data-target="#closeBtn"><span class="icon-cancel" aria-hidden="true"></span>'
			. Text::_('JLIB_HTML_BEHAVIOR_CLOSE') . '</button>'
			. '<button type="button" class="button-save btn btn-sm btn-success hidden" data-target="#saveBtn"><span class="icon-save" aria-hidden="true"></span>'
			. Text::_('JSAVE') . '</button>',
	)
);

// Hide subhead
$this->document->addStyleDeclaration('#subhead{display:none;}');
?>
<div class="alert alert-warning" role="alert">
	<div class="alert-heading"><?php echo Text::_('COM_JSOFTMART_DEVELOPMENT'); ?></div>
	<div><?php echo Text::_('COM_JSOFTMART_DEVELOPMENT_DESCRIPTION'); ?></div>
</div>
<div class="com_cpanel">
	<div id="cpanel-modules">
		<?php if ($this->quickicons) : ?>
			<div class="cpanel-modules <?php echo $this->position; ?>-quickicons">
				<div class="card-columns">
					<?php foreach ($this->quickicons as $iconmodule)
					{
						echo ModuleHelper::renderModule($iconmodule, array('style' => 'well'));
					} ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="cpanel-modules <?php echo $this->position; ?>">
			<div class="card-columns">
				<?php foreach ($this->modules as $module)
				{
					echo ModuleHelper::renderModule($module, array('style' => 'well'));
				} ?>
			</div>
		</div>
		<?php if (Factory::getUser()->authorise('core.create', 'com_modules')) : ?>
			<div class="row">
				<div class="col-md-6">
					<a href="#moduleEditModal" data-toggle="modal" data-target="#moduleDashboardAddModal" role="button"
					   class="cpanel-add-module text-center py-5 w-100 d-block">
						<div class="cpanel-add-module-icon text-center">
							<span class="fa fa-plus-square text-light mt-2"></span>
						</div>
						<span><?php echo Text::_('COM_CPANEL_ADD_DASHBOARD_MODULE'); ?></span>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>