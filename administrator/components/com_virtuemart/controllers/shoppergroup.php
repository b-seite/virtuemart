<?php
/**
*
* Shopper Group controller
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
* @version $Id: shoppergroup.php 8618 2014-12-10 22:45:48Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JLoader::register('VmController', VMPATH_ADMIN .'/helpers/vmcontroller.php');

/**
 * Shopper Group Controller
 *
 * @package    VirtueMart
 * @subpackage ShopperGroup
 * @author Markus �hler
 */
class VirtuemartControllerShopperGroup extends VmController
{
	/**
	 * Method to display the view
	 *
	 * @access	public
	 */
	function __construct() {
		parent::__construct('virtuemart_shoppergroup_id');
	}

}
// pure php no closing tag
