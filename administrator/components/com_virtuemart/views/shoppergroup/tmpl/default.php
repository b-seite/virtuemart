<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage ShopperGroup
* @author Markus �hler
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 9345 2016-11-01 19:31:24Z kkmediaproduction $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

AdminUIHelper::startAdminArea($this);

?>

<form action="index.php?option=com_virtuemart&view=shoppergroup" method="post" name="adminForm" id="adminForm">
<?php if ($this->task=='massxref_sgrps' or $this->task=='massxref_sgrps_exe') : ?>
<div id="header">
<div id="massxref_task">
	<table class="">
		<tr>
			<td align="left">
				<?php echo vmText::_('COM_VIRTUEMART_PRODUCT_XREF_TASK') ?>
			</td>
			<td>
				<?php
				$options = array(
				'replace' => vmText::_('COM_VIRTUEMART_PRODUCT_XREF_TASK_REPLACE'),
				'add' => vmText::_('COM_VIRTUEMART_PRODUCT_XREF_TASK_ADD'),
				'remove' => vmText::_('COM_VIRTUEMART_PRODUCT_XREF_TASK_REMOVE')
				);
				echo VmHTML::selectList('massxref_task', 'replace', $options);
				?>
			</td>
		</tr>
	</table>
</div>
</div>
<?php endif; ?>
  <div id="editcell">
	  <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
		<thead>
		  <tr>
			<th width ="1%" class="center">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
			</th>
			<th min-width="30px" class="nowrap center" >
				<?php echo vmText::_('COM_VIRTUEMART_STATUS'); ?>
			</th>
			<th width="10%" class ="nowrap">
				<?php echo $this->sort('shopper_group_name','COM_VIRTUEMART_SHOPPERGROUP_NAME'); ?>
			</th>
			<th>
				<?php echo $this->sort('shopper_group_desc','COM_VIRTUEMART_SHOPPERGROUP_DESCRIPTION'); ?>
			</th>
			
			<?php if((Vmconfig::get('multix','none')!='none') && $this->showVendors){ ?>
			<th width="1%" class="nowrap hidden-phone">
				<?php echo $this->sort('virtuemart_vendor_id','COM_VIRTUEMART_VENDOR'); ?>
			</th>
			<?php } ?>
			<th width="1%" class="nowrap hidden-phone" >
				<?php echo $this->sort('sgrp_additional','COM_VIRTUEMART_ADDITIONAL'); ?>
			</th>
			<th width="1%" class="nowrap hidden-phone">
				<?php echo $this->sort('virtuemart_shoppergroup_id', 'COM_VIRTUEMART_ID');  ?>
			</th>
		  </tr>
		</thead><?php

		$k = 0;
		$i = 0;
		foreach ($this->shoppergroups as $key => $shoppergroup) {
			
			
			if ($shoppergroup->default == 0) {
				$status = $this->gridPublished( $shoppergroup, $i );
				$checked = JHtml::_('grid.id', $i, $shoppergroup->virtuemart_shoppergroup_id,null,'virtuemart_shoppergroup_id');
				$isdefault = FALSE;
			} else {
				$status = $this->toggle($shoppergroup->default, $i, 'toggle.default','lock','unlock',TRUE );
				$checked = '<input type="checkbox" disabled class="hasTooltip">';
				$isdefault = TRUE;
			}
			
			$is_additional = $this->toggle($shoppergroup->sgrp_additional, $i, 'toggle.sgrp_additional', '', '', $isdefault);
			
			$editlink = JROUTE::_('index.php?option=com_virtuemart&view=shoppergroup&task=edit&virtuemart_shoppergroup_id[]=' . $shoppergroup->virtuemart_shoppergroup_id);

			?>

		  <tr class="row<?php echo $k ; ?>">
			<td class="center">
				<?php echo $checked; ?>
			</td>
			<td class="center">
				<?php echo $status; ?>
			</td>
			<td class="left">
			  <a href="<?php echo $editlink; ?>"><?php echo vmText::_($shoppergroup->shopper_group_name); ?></a>
			</td>
			<td class="left">
				<?php echo vmText::_($shoppergroup->shopper_group_desc); ?>
			</td>
			
			<?php if((Vmconfig::get('multix','none')!='none') && $this->showVendors){ ?>
			<td class="left">
				<?php echo $shoppergroup->virtuemart_vendor_id; ?>
			</td>
			<?php } ?>
			<td class="center hidden-phone">
				<?php 
					echo($is_additional);
				?>
			</td>
			<td class="left hidden-phone">
				<?php echo $shoppergroup->virtuemart_shoppergroup_id; ?>
			</td>
		  </tr><?php
			$k = 1 - $k;
			$i++;
		} ?>
		<tfoot>
		  <tr>
			<td colspan="10">
				<?php echo $this->sgrppagination->getListFooter(); ?>
			</td>
		  </tr>
		</tfoot>
	  </table>
  </div>

	<?php echo $this->addStandardHiddenToForm($this->_name,$this->task); ?>
</form><?php
AdminUIHelper::endAdminArea(); ?>