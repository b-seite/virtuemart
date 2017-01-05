<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Coupon
* @author RickG
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 9257 2016-07-04 14:40:20Z kkmediaproduction $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.formvalidator');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

?>

<form action=<?php echo JRoute::_('index.php?option=com_virtuemart&view=coupon'); ?> method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo JLayoutHelper::render('sidemenu'); ?>
	</div>
	<div id="j-main-container" class="span10">

	<div id="header">
		<div id="filterbox">
			<table>
				<tr>
					<td align="left" width="100%">
						<?php echo vmText::_('COM_VIRTUEMART_FILTER'); ?>:
						<input type="text" name="filter_ratings" value="<?php echo vRequest::getVar('filter_ratings', ''); ?>" />
						<button class="btn btn-small" onclick="this.form.submit();"><?php echo vmText::_('COM_VIRTUEMART_GO'); ?></button>
						<button class="btn btn-small" onclick="document.adminForm.filter_ratings.value='';"><?php echo vmText::_('COM_VIRTUEMART_RESET'); ?></button>
						<?php if($this->showVendors()){
							echo Shopfunctions::renderVendorList(vmAccess::getVendorId());
						} ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="resultscounter" ><?php echo $this->pagination->getResultsCounter();?></div>
	</div>
    <div id="editcell">
	    <?php if (empty($this->coupons)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
	    <table class="table table-striped" id="couponList">
	    <thead>
		<tr>
			
		    <th width="1%" class="center">
				<?php echo JHtml::_('grid.checkall'); ?>
		    </th>
		    <th width="25%">
			    <?php echo $this->sort('coupon_code','COM_VIRTUEMART_COUPON_CODE'); ?> 
		    </th>
		    <th width="10%" class="nowrap hidden-phone">
			    <?php echo $this->sort('percent_or_total','COM_VIRTUEMART_COUPON_PERCENT_TOTAL'); ?> 
		    </th>
		    <th width="16%">
			    <?php echo $this->sort('coupon_type','COM_VIRTUEMART_COUPON_TYPE'); ?> 
		    </th>
		    <th width="16%">
			    <?php echo $this->sort('coupon_value','COM_VIRTUEMART_VALUE'); ?> 
		    </th>
		    <th min-width="130px" class="nowrap">
			    <?php echo $this->sort('coupon_value_valid','COM_VIRTUEMART_COUPON_VALUE_VALID_AT'); ?> 
		    </th>
			<th min-width="100px" class="nowrap">
				<?php echo $this->sort('coupon_used','COM_VIRTUEMART_COUPON_USED'); ?> 
			</th>
		    <th>
			     <?php echo $this->sort('virtuemart_coupon_id', 'COM_VIRTUEMART_ID')  ?>
			</th>
		</tr>
	    </thead>
	    <tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	    <?php
		    $i = 0;
			$k = 0;
			foreach ($this->coupons as $key => $coupon) {
	    
		$checked = JHtml::_('grid.id', $i, $coupon->virtuemart_coupon_id);
		$used = $this->toggle($coupon->coupon_used, $i,'toggle.coupon_used','','',TRUE);
		
		$editlink = JROUTE::_('index.php?option=com_virtuemart&view=coupon&task=edit&cid[]=' . $coupon->virtuemart_coupon_id);
		?>
	    <tr class="row<?php echo $k; ?>" sortable-group-id="<?php echo $item->catid; ?>">
		    
		<td class="admin-checkbox">
			<?php echo $checked; ?>
		</td>
		<td class="left">
		    <a href="<?php echo $editlink; ?>"><?php echo $coupon->coupon_code; ?></a>
		</td>
		<td>
			<?php echo vmText::_('COM_VIRTUEMART_COUPON_'.strtoupper($coupon->percent_or_total)); ?>
		</td>
		<td class="left">
			<?php echo vmText::_('COM_VIRTUEMART_COUPON_TYPE_'.strtoupper($coupon->coupon_type)); ?>
		</td>
		<td>
			<?php echo vmText::_($coupon->coupon_value); ?>
		    <?php if ( $coupon->percent_or_total=='percent') echo '%' ;
		    else echo $this->vendor_currency;   ?>
		</td>
		<td class="left">
			<?php echo vmText::_($coupon->coupon_value_valid); 
			echo $this->vendor_currency; ?>
		</td>
		    <td class="center">
			    <?php
			    if( $coupon->coupon_type=='gift'){
				    echo $used; 
			     }
			    ?>
		    </td>
		<td align="left">
			<?php echo vmText::_($coupon->virtuemart_coupon_id); ?>
		</td>
	    </tr>
		<?php
		$k = 1 - $k;
		$i++;
	    }
	    ?>
	    
	</table>
	<?php endif; ?>
    </div>

	<?php echo $this->addStandardHiddenToForm($this->_name); ?>	
</div>
</form>
