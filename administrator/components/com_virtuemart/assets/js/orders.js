/**
 * orders.js: functions for Order administration
 *
 * @package	VirtueMart
 * @subpackage Javascript Library
 * @author Max Milbers
 * @copyright Copyright (c) 2016 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

function onReadyOrders(){
	jQuery(document).ready(function() {
		jQuery(".orderstatus_select").change( function() {

			var name = jQuery(this).attr("name");
			var brindex = name.indexOf("orders[");
			if ( brindex >= 0){
				//yeh, yeh, maybe not the most elegant way, but it does, what it should
				var s = name.indexOf("[")+1;
				var e = name.indexOf("]");
				var id = name.substring(s,e);

				var selected = jQuery(this).val();
				var selStr = "[name=\"orders["+id+"][customer_notified]\"]";
				var elem = jQuery(selStr);

				if(jQuery.inArray(selected, Virtuemart.orderstatus)!=-1){
					elem.attr("checked",true);
					// for the checkbox
					jQuery(this).parent().parent().find("input[name=\"cid[]\"]").attr("checked",true);
				} else {
					elem.attr("checked",false);
				}
			}
		});

		jQuery('.show_comment').click(function() {
			jQuery(this).prev('.element-hidden').show();
			return false
		});
		jQuery('.element-hidden').mouseleave(function() {
			jQuery(this).hide();
		});
		jQuery('.element-hidden').mouseout(function() {
			jQuery(this).hide();
		});

	});
}

function onReadyOrder(){
	jQuery(document).ready(function() {
		jQuery('.show_element').click(function() {
			jQuery('.element-hidden').toggle();
			jQuery('select').trigger('chosen:updated');
			return false;
		});
		jQuery('.updateOrderItemStatus').click(function() {
			document.orderItemForm.task.value = 'updateOrderItemStatus';
			document.orderItemForm.submit();
			return false;
		});
		jQuery('.updateOrder').click(function() {
			document.orderForm.submit();
			return false;
		});
		jQuery('.createOrder').click(function() {
			document.orderForm.task.value = 'CreateOrderHead';
			document.orderForm.submit();
			return false;
		});
		jQuery('.newOrderItem').click(function() {
			document.orderItemForm.task.value = 'newOrderItem';
			document.orderItemForm.submit();
			return false;
		});
		jQuery('.orderStatFormSubmit').click(function() {
			//document.orderStatForm.task.value = 'updateOrderItemStatus';
			document.orderStatForm.submit();

			return false;
		});

});
}

function confirmation(destnUrl) {
	var answer = confirm(Virtuemart.confirmDelete);
	if (answer) {
		window.location = destnUrl;
	}
}

function set2status(){

	var newStatus = jQuery("#order_status_code_bulk").val();

	var customer_notified = jQuery("input[name=\'customer_notified\']").is( ":checked" );
	var customer_send_comment = jQuery("input[name=\'customer_send_comment\']").is( ":checked" );
	var update_lines = jQuery("input[name=\'update_lines\']").is( ":checked" );
	var comments = jQuery("textarea[name=\'comments\']").val();

	field = document.getElementsByName("cid[]");
	var fname = "";
	var sel = 0;
	for (i = 0; i < field.length; i++){
		if (field[i].checked){
			fname = "orders[" + field[i].value + "]";
			jQuery("input[name=\'"+fname+"[customer_notified]\']").prop("checked",customer_notified);
			jQuery("input[name=\'"+fname+"[customer_send_comment]\']").prop("checked",customer_send_comment);
			jQuery("input[name=\'"+fname+"[update_lines]\']").prop("checked",update_lines);
			jQuery("textarea[name=\'"+fname+"[comments]\']").text(comments);
			jQuery("#order_status"+i).val(newStatus).trigger("chosen:updated").trigger("liszt:updated");
		}
	}
}

jQuery( function($) {

	$('.orderedit').hide();
	$('.ordereditI').show();
	$('.orderedit').css('backgroundColor', 'lightgray');

	$('.updateOrderItemStatus').click(function() {
		document.orderItemForm.task.value = 'updateOrderItemStatus';
		document.orderItemForm.submit();
		return false
	});

	$('select#virtuemart_paymentmethod_id').change(function(){
		$('span#delete_old_payment').show();
		$('input#delete_old_payment').attr('checked','checked');
	});

});

function enableEdit(e)
{
	jQuery('.orderedit').each( function()
	{
		var d = jQuery(this).css('visibility')=='visible';
		jQuery(this).toggle();
		jQuery('.orderedit').css('backgroundColor', d ? 'white' : 'lightgray');
		jQuery('.orderedit').css('color', d ? 'blue' : 'black');
	});
	jQuery('.ordereditI').each( function()
	{
		jQuery(this).toggle();
	});
	e.preventDefault();
};

function addNewLine(e,i) {

	var row = jQuery('#itemTable').find('#lItemRow').html();
	var needle = 'item_id['+i+']';
	//var needle = new RegExp('item_id['+i+']','igm');
	while (row.indexOf(needle) !== -1){
		row = row.replace(needle,'item_id[0]');
	}

	jQuery('#itemTable').find('#lItemRow').after('<tr>'+row+'</tr>');
	e.preventDefault();
};

function cancelEdit(e) {
	jQuery('#orderItemForm').each(function(){
		this.reset();
	});
	jQuery('.selectItemStatusCode')
		.find('option:selected').prop('selected', true)
		.end().trigger('liszt:updated');
	jQuery('.orderedit').hide();
	jQuery('.ordereditI').show();
	e.preventDefault();
}

function resetOrderHead(e) {
	jQuery('#orderForm').each(function(){
		this.reset();
	});
	jQuery('select#virtuemart_paymentmethod_id')
		.find('option:selected').prop('selected', true)
		.end().trigger('liszt:updated');
	jQuery('select#virtuemart_shipmentmethod_id')
		.find('option:selected').prop('selected', true)
		.end().trigger('liszt:updated');
	e.preventDefault();
}



