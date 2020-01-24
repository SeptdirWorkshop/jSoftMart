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

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$defaultTab  = 'content_' . $this->state->get('translation.default');
$contentTabs = array();
foreach (LanguageHelper::getLanguages('lang_code') as $code => $language)
{
	$default = ($this->state->get('translation.default') === $code);
	$title   = HTMLHelper::_('image', 'mod_languages/' . $language->image . '.gif', '', null, true)
		. '&nbsp;' . $language->title;
	if ($default)
	{
		$title .= '<span class="star">&nbsp;*</span>';
	}

	$tab           = new stdClass();
	$tab->code     = $code;
	$tab->title    = $title;
	$tab->default  = $default;
	$tab->orgering = ($default) ? 0 : 1;

	$contentTabs[] = $tab;
}
$contentTabs = ArrayHelper::sortObjects($contentTabs, 'orgering', 1);
?>
<form action="<?php echo Route::_('index.php?option=com_jsoftmart&view=category&layout=edit&id=' . (int) $this->item->id); ?>"
	  method="post" name="adminForm" id="category-form" class="form-validate">
	<div class="row">
		<div class="col-lg-8">
			<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', array('active' => $defaultTab)); ?>

			<?php foreach ($contentTabs as $tab): ?>
				<?php $form = (isset($this->translationForms[$tab->code])) ? $this->translationForms[$tab->code] : false;
				if (!$form) continue;
				echo HTMLHelper::_('uitab.addTab', 'myTab', 'content_' . $tab->code, $tab->title); ?>
				<div class="row">
					<?php foreach ($form->getFieldsets() as $fieldset): ?>
						<div class="col-lg-<?php echo ($fieldset->name === 'content') ? '12' : '6'; ?>">
							<div class="card mb-3">
								<div class="card-body">
									<div class="h3 card-title"><?php echo Text::_($fieldset->label); ?></div>
									<div class="card-text">
										<?php echo $form->renderFieldset($fieldset->name); ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<?php endforeach; ?>

			<?php if (count($this->form->getFieldsets()) > 1): ?>
				<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'plugins', Text::_('COM_JSOFTMART_PLUGINS')); ?>
				<div class="row">
					<?php foreach ($this->form->getFieldsets() as $fieldset):
						if ($fieldset->name === 'global') continue; ?>
						<div class="col-lg-6">
							<div class="card mb-3">
								<div class="card-body">
									<div class="h3 card-title"><?php echo Text::_($fieldset->label); ?></div>
									<div class="card-text">
										<?php echo $this->form->renderFieldset($fieldset->name); ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<?php endif; ?>
			<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
		</div>
		<div class="col-lg-4">
			<div class="card mb-3">
				<div class="card-body">
					<?php echo $this->form->renderFieldset('global'); ?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

