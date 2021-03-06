/**
 * catreeajax.js: load category tree by ajax
 *
 * @package	VirtueMart
 * @subpackage Javascript Library
 * @author Max Milbers
 * @copyright Copyright (c) 2016 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
// vmText::sprintf( 'COM_VIRTUEMART_SELECT' ,  vmText::_('COM_VIRTUEMART_CATEGORY'))

//Virtuemart.empty;
//Virtuemart.param;
if (typeof Virtuemart === "undefined")
	Virtuemart = {};
Virtuemart.startVmLoading = function(a) {
	var msg = '';
	/*if (typeof a.data.msg !== 'undefined') {
	 msg = a.data.msg;
	 }*/
	jQuery('#pro-tech_ajax_load').addClass('vmLoading');
	if (!jQuery('div.vmLoadingDiv').length) {
		jQuery('body').append('<div class=\"vmLoadingDiv\"><div class=\"vmLoadingDivMsg\">' + msg + '</div></div>');
	}
};

Virtuemart.stopVmLoading = function() {
	if (jQuery('#pro-tech_ajax_load').hasClass('vmLoading')) {
		jQuery('body').removeClass('vmLoading');
		jQuery('div.vmLoadingDiv').remove();
	}
};

Virtuemart.loadCategoryTree = function(id){
	jQuery('#'+id+'_chzn').remove();
	jQuery('<div id=\"pro-tech_ajax_load\" style=\"max-width:220px;\">Loading</div>').insertAfter('select#'+id);
	Virtuemart.startVmLoading('Loading categories');

	jQuery.ajax({
		type: 'GET',
		url: 'index.php',
		data: 'option=com_virtuemart&view=product&type=getCategoriesTree'+Virtuemart.param+'&format=json',
		success:function(json){
			jQuery('select#'+id).switchClass('chzn-done','chzn-select');
			jQuery('select#'+id).html('<option value=\"\">'+Virtuemart.emptyCatOpt+'</option>'+json.value);
			jQuery('#pro-tech_ajax_load').remove();
			jQuery('select#'+id).chosen();
			Virtuemart.stopVmLoading();
		}
	});
};