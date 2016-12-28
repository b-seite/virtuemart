<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage Calculation tool
* @author Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: edit.php 3617 2011-07-05 12:55:12Z enytheme $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
vmJsApi::jDate();

// if (!class_exists('vmCalculationPlugin')) require(VMPATH_PLUGINLIBS . DS . 'vmcalculationplugin.php');
		// JPluginHelper::importPlugin('vmcalculation');
		// $dispatcher = & JDispatcher::getInstance();
		// $html = '';
		// $returnValues = $dispatcher->trigger('plgVmOnDisplayEdit', array('vmcalculation' , $html));
		// print_r( $returnValues );

?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
<div class="row">
<div class="span6">
	<fieldset>
	<legend><?php echo vmText::_('COM_VIRTUEMART_CALC_DETAILS'); ?></legend>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_CALC_NAME','calc_name',$this->calc->calc_name,'class="required"'); ?>
		<?php echo VmHTML::row('booleanlist','COM_VIRTUEMART_PUBLISHED','published',$this->calc->published); ?>
		<?php if($this->showVendors() ){
		echo VmHTML::row('checkbox','COM_VIRTUEMART_SHARED', 'shared', $this->calc->shared );
		} ?>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_ORDERING','ordering',$this->calc->ordering,'class="inputbox"','',4,4); ?>
		<?php echo VmHTML::row('input','COM_VIRTUEMART_DESCRIPTION','calc_descr',$this->calc->calc_descr,'class="inputbox"','',70,255); ?>
		<?php echo VmHTML::row('genericlist','COM_VIRTUEMART_CALC_KIND', $this->entryPointsList, 'calc_kind', '', 'calc_kind', 'calc_kind_name', $this->calc->calc_kind ); ?>
		<?php echo VmHTML::row('genericlist','COM_VIRTUEMART_CALC_VALUE_MATHOP', $this->mathOpList, 'calc_value_mathop', 'style="width:70px;"', 'calc_value_mathop', 'calc_value_mathop_name', $this->calc->calc_value_mathop ); ?>
		
		<?php echo VmHTML::row('input','COM_VIRTUEMART_VALUE','calc_value',$this->calc->calc_value); ?>
		<?php echo VmHTML::row('select','COM_VIRTUEMART_CURRENCY', 'calc_currency', $this->currencies ,$this->calc->calc_currency,'','virtuemart_currency_id', 'currency_name',false) ; ?>
	</fieldset>
</div>
<div class="span6">
	<fieldset>
		<legend for="calc_categories"><?php echo vmText::_('COM_VIRTUEMART_CATEGORY'); ?></legend>
			
				<select class="inputbox multiple" id="calc_categories" name="calc_categories[]" multiple="multiple" size="10">
					<?php echo $this->categoryTree; ?>
				</select>
			
		<?php echo VmHTML::row('genericlist','COM_VIRTUEMART_SHOPPERGROUP_IDS', $this->shopperGroupList, 'virtuemart_shoppergroup_id', '', 'shopper_group_name','shopper_group_name', $this->calc->virtuemart_shoppergroup_ids ); ?>
		<?php echo VmHTML::row('genericlist','COM_VIRTUEMART_COUNTRY', $this->countriesList ); ?>
		<?php echo VmHTML::row('genericlist','COM_VIRTUEMART_STATE_S', $this->statesList ); ?>
		<?php echo VmHTML::row('genericlist','COM_VIRTUEMART_MANUFACTURER', $this->manufacturerList ); /* Mod. <mediaDESIGN> St.Kraft 2013-02-24 Herstellerrabatt */ ?>

		<?php //echo VmHTML::row('booleanlist','COM_VIRTUEMART_VISIBLE_FOR_SHOPPER','calc_shopper_published',$this->calc->calc_shopper_published); ?>
		<?php //echo VmHTML::row('booleanlist','COM_VIRTUEMART_VISIBLE_FOR_VENDOR','calc_vendor_published',$this->calc->calc_vendor_published); ?>
		<?php
			echo VmHTML::row('raw','COM_VIRTUEMART_START_DATE', vmJsApi::jDate($this->calc->publish_up, 'publish_up') ); ?>
		<?php
			echo VmHTML::row('raw','COM_VIRTUEMART_END_DATE',  vmJsApi::jDate($this->calc->publish_down, 'publish_down') ); ?>

        </fieldset>
		<?php

		if (!class_exists('vmCalculationPlugin')) require(VMPATH_PLUGINLIBS . DS . 'vmcalculationplugin.php');
		JPluginHelper::importPlugin('vmcalculation');
		$dispatcher = JDispatcher::getInstance();
		$html ='';
		$returnValues = $dispatcher->trigger('plgVmOnDisplayEdit', array(&$this->calc,&$html));
		echo $html;

		if($this->showVendors() ){
			echo VmHTML::row('raw','COM_VIRTUEMART_VENDOR', $this->vendorList );
		}
		?>


</div>

	<input type="hidden" name="virtuemart_calc_id" value="<?php echo $this->calc->virtuemart_calc_id; ?>" />

	<?php echo $this->addStandardHiddenToForm(); ?>
</div>
</form>
