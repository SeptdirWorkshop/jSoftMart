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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\JSoftMart\Administrator\Helper\TranslationHelper;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$translationsTabs = [];
foreach (TranslationHelper::getTranslations() as $code => $translation)
{
	$title = HTMLHelper::_('image', 'mod_languages/' . $translation->image . '.gif', '', null, true)
		. '&nbsp;' . $translation->name;
	if ($translation->default)
	{
		$title .= '<span class="star">&nbsp;*</span>';
	}

	$tab                = new \stdClass;
	$tab->code          = $code;
	$tab->title         = $title;
	$translationsTabs[] = $tab;
}
?>
<form action="<?php echo Route::_('index.php?option=com_jsoftmart&view=category&layout=edit&id=' . (int) $this->item->id); ?>"
	  method="post" name="adminForm" id="category-form" class="form-validate">
	<div class="row">
		<div class="col-lg-8">
			<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => TranslationHelper::getDefault()]); ?>
			<?php foreach ($translationsTabs as $tab)
			{
				$this->tab             = $tab;
				$this->translationForm = (isset($this->translationsForms[$tab->code])) ? $this->translationsForms[$tab->code] : false;
				echo $this->loadTemplate('translation');
			} ?>

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
			<?php echo $this->form->renderFieldset('global'); ?>
		</div>
	</div>
	<input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>