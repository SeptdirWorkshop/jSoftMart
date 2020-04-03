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

if (!$this->translationForm) return;

?>
<?php echo HTMLHelper::_('uitab.addTab', 'myTab', $this->tab->code, $this->tab->title); ?>
<?php foreach ($this->translationForm->getFieldsets() as $fieldset): ?>
	<fieldset class="options-form">
		<legend><?php echo Text::_($fieldset->label); ?></legend>
		<div>
			<?php echo $this->translationForm->renderFieldset($fieldset->name); ?>
		</div>
	</fieldset>
<?php endforeach; ?>
<?php echo HTMLHelper::_('uitab.endTab'); ?>