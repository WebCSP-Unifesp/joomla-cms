<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

JHtml::_('behavior.core');

HTMLHelper::_('script', 'com_tags/list-default.js', ['relative' => true, 'version' => 'auto']);

$n         = count($this->items);
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
	<?php if ($this->params->get('filter_field') || $this->params->get('show_pagination_limit')) : ?>
		<fieldset class="filters d-flex justify-content-between mb-3">
			<?php if ($this->params->get('filter_field')) : ?>
				<div class="input-group">
					<label class="filter-search-lbl sr-only" for="filter-search">
						<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL') . '&#160;'; ?>
					</label>
					<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>" class="form-control" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_TAGS_FILTER_SEARCH_DESC'); ?>" placeholder="<?php echo JText::_('COM_TAGS_TITLE_FILTER_LABEL'); ?>">
					<span class="input-group-append">
						<button type="submit" name="filter-search-button" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" class="btn btn-secondary">
							<span class="fa fa-search" aria-hidden="true"></span>
						</button>
						<button type="reset" name="filter-clear-button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" class="btn btn-secondary">
							<span class="fa fa-times" aria-hidden="true"></span>
						</button>
					</span>
				</div>
			<?php endif; ?>
			<?php if ($this->params->get('show_pagination_limit')) : ?>
				<div class="btn-group float-right">
					<label for="limit" class="sr-only">
						<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
					</label>
					<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			<?php endif; ?>

			<input type="hidden" name="filter_order" value="">
			<input type="hidden" name="filter_order_Dir" value="">
			<input type="hidden" name="limitstart" value="">
			<input type="hidden" name="task" value="">
		</fieldset>
	<?php endif; ?>

	<?php if ($this->items === false || $n === 0) : ?>
		<p><?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php else : ?>
		<table class="category table table-striped table-bordered table-hover">
			<?php if ($this->params->get('show_headings')) : ?>
				<thead>
					<tr>
						<th id="categorylist_header_title">
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'c.core_title', $listDirn, $listOrder); ?>
						</th>
						<?php if ($date = $this->params->get('tag_list_show_date')) : ?>
							<th id="categorylist_header_date">
								<?php if ($date === 'created') : ?>
									<?php echo JHtml::_('grid.sort', 'COM_TAGS_' . $date . '_DATE', 'c.core_created_time', $listDirn, $listOrder); ?>
								<?php elseif ($date === 'modified') : ?>
									<?php echo JHtml::_('grid.sort', 'COM_TAGS_' . $date . '_DATE', 'c.core_modified_time', $listDirn, $listOrder); ?>
								<?php elseif ($date === 'published') : ?>
									<?php echo JHtml::_('grid.sort', 'COM_TAGS_' . $date . '_DATE', 'c.core_publish_up', $listDirn, $listOrder); ?>
								<?php endif; ?>
							</th>
						<?php endif; ?>

					</tr>
				</thead>
			<?php endif; ?>
			<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php if ($this->items[$i]->core_state == 0) : ?>
						<tr class="table-danger">
					<?php else : ?>
						<tr>
					<?php endif; ?>
						<td <?php if ($this->params->get('show_headings')) echo "headers=\"categorylist_header_title\""; ?> class="list-title">
							<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
								<?php echo $this->escape($item->core_title); ?>
							</a>
							<?php if ($item->core_state == 0) : ?>
								<span class="list-published badge badge-warning">
									<?php echo JText::_('JUNPUBLISHED'); ?>
								</span>
							<?php endif; ?>
						</td>
						<?php if ($this->params->get('tag_list_show_date')) : ?>
							<td headers="categorylist_header_date" class="list-date small">
								<?php
								echo JHtml::_(
									'date', $item->displayDate,
									$this->escape($this->params->get('date_format', JText::_('DATE_FORMAT_LC3')))
								); ?>
							</td>
						<?php endif; ?>

					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>

	<?php // Add pagination links ?>
	<?php if (!empty($this->items)) : ?>
		<?php if (($this->params->def('show_pagination', 2) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
			<div class="w-100">
				<?php if ($this->params->def('show_pagination_results', 1)) : ?>
					<p class="counter float-right pt-3 pr-2">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
				<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</form>
