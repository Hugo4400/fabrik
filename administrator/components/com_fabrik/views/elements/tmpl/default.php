<?php
/**
 * @package Joomla
 * @subpackage Fabrik
 * @copyright Copyright (C) 2005 Rob Clayburn. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT.DS.'helpers'.DS.'html');
JHtml::_('behavior.tooltip');
JHTML::_('script','system/multiselect.js', false, true);
$user	= JFactory::getUser();
$userId	= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder	= $listOrder == 'e.ordering';

?>
<form action="<?php echo JRoute::_('index.php?option=com_fabrik&view=elements'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?>:</label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_FABRIK_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">

			<select name="filter_form" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_FABRIK_SELECT_FORM');?></option>
				<?php echo JHtml::_('select.options', $this->formOptions, 'value', 'text', $this->state->get('filter.form'), true);?>
			</select>

			<select name="filter_group" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_FABRIK_SELECT_GROUP');?></option>
				<?php echo JHtml::_('select.options', $this->groupOptions, 'value', 'text', $this->state->get('filter.group'), true);?>
			</select>

			<select name="filter_plugin" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_FABRIK_SELECT_PLUGIN')?></option>
				<?php echo JHtml::_('select.options', $this->pluginOptions, 'value', 'text', $this->state->get('filter.plugin'), true)?>
			</select>

		<select name="filter_showinlist" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_FABRIK_SELECT_SHOW_IN_LIST');?></option>
				<?php echo JHtml::_('select.options', $this->showInListOptions, 'value', 'text', $this->state->get('filter.showinlist'), true);?>
			</select>

			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived'=>false)), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>

		</div>
	</fieldset>
	<div class="clr"> </div>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="2%"></th>
				<th width="2%"><?php echo JHTML::_( 'grid.sort', 'JGRID_HEADING_ID', 'e.id', $listDirn, $listOrder); ?></th>
				<th width="1%"> <input type="checkbox" name="toggle" value="" onclick="checkAll(this);" /> </th>
				<th width="15%" >
					<?php echo JHTML::_('grid.sort', 'COM_FABRIK_NAME', 'e.name', $listDirn, $listOrder); ?>
				</th>
				<th width="15%">
					<?php echo JHTML::_('grid.sort', 'COM_FABRIK_LABEL', 'e.label', $listDirn, $listOrder); ?>
				</th>
				<th width="20%">
					<?php echo JText::_('COM_FABRIK_FULL_ELEMENT_NAME');?>
				</th>
				<th width="15%">
				<?php echo JHTML::_('grid.sort', 'COM_FABRIK_GROUP', 'g.label', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHTML::_('grid.sort', 'COM_FABRIK_PLUGIN', 'e.plugin', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
					<?php echo JHTML::_('grid.sort', 'COM_FABRIK_SHOW_IN_LIST', 'e.show_in_list_summary', $listDirn, $listOrder); ?>
				</th>
				<th width="5%">
				<?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'e.published', $listDirn, $listOrder); ?>
				</th>
				<th width="10%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'e.ordering', $listDirn, $listOrder); ?>
					<?php if ($saveOrder) :?>
					<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'elements.saveorder'); ?>
					<?php  endif;?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
			$ordering	= ($listOrder == 'e.ordering');
			$link = JRoute::_('index.php?option=com_fabrik&task=element.edit&id='.(int)$item->id);
			$canCreate	= $user->authorise('core.create',		'com_fabrik.element.'.$item->group_id);
			$canEdit	= $user->authorise('core.edit',			'com_fabrik.element.'.$item->group_id);
			$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
			$canChange	= $user->authorise('core.edit.state',	'com_fabrik.element.'.$item->group_id) && $canCheckin;
			?>

			<tr class="row<?php echo $i % 2; ?>">
				<td>
				<?php if ($item->parent_id != 0) {
					echo "<a href='index.php?option=com_fabrik&task=element.edit&id=".$item->parent_id."'>"
					. JHTML::image('media/com_fabrik/images/child_element.png', JText::_('COM_FABRIK_LINKED_ELEMENT'), 'title="'.JText::_('COM_FABRIK_LINKED_ELEMENT').'"')
					.'</a>&nbsp';
				}else{
					echo JHTML::image('media/com_fabrik/images/parent_element.png', JText::_('COM_FABRIK_PARENT_ELEMENT'), 'title="'.JText::_('COM_FABRIK_PARENT_ELEMENT').'"').'&nbsp;';
				}?>
					</td>
					<td><?php echo $item->id; ?></td>
					<td><?php echo JHtml::_('grid.id', $i, $item->id); ?></td>
					<td>
						<?php
						if ($item->checked_out && ( $item->checked_out != $user->get('id'))) {
							echo  $item->name;
						} else {
						?>
						<a href="<?php echo $link; ?>">
							<?php echo $item->name; ?>
						</a>
					<?php } ?>
					</td>
					<td>
						<?php echo $item->label; ?>
					</td>
					<td>
						<?php echo $item->full_element_name; ?>
					</td>
					<td>
						<a href="index.php?option=com_fabrik&task=group.edit&id=<?php echo $item->group_id?>">
							<?php echo $item->group_name; ?>
						</a>
					</td>
					<td>
						<?php echo $item->plugin; ?>
					</td>
					<td>
						<?php echo JHtmlGrid::boolean($i, $item->show_in_list_summary, 'elements.showInListView', 'elements.hideFromListView');?>
					</td>
					<td>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'elements.', $canChange);?>
					</td>
					<td class="order">
						<?php if ($saveOrder) :?>
							<?php if ($listDirn == 'asc') : ?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->group_id == @$this->items[$i-1]->group_id), 'elements.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->group_id == @$this->items[$i+1]->group_id), 'elements.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php elseif ($listDirn == 'desc') :
							echo $this->items[$i-1]->group_id;?>
								<span><?php echo $this->pagination->orderUpIcon($i, ($item->group_id == @$this->items[$i-1]->group_id), 'elements.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
								<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->group_id == @$this->items[$i+1]->group_id), 'elements.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
							<?php endif; ?>
						<?php endif;?>
						<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="text-area-order" />
					</td>
				</tr>

			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>
