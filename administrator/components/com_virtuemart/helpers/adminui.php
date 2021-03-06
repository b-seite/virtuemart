<?php
/**
 * Administrator menu helper class
 *
 * This class was derived from the show_image_in_imgtag.php and imageTools.class.php files in VM.  It provides some
 * image functions that are used throughout the VirtueMart shop.
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Eugen Stranz, Max Milbers
 * @copyright Copyright (c) 2004-2008 Soeren Eberhardt-Biermann, 2009-2016 VirtueMart Team. All rights reserved.
 */

// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ();

class AdminUIHelper {

	public static $vmAdminAreaStarted = false;
	public static $backEnd = true;

	/**
	 * Start the administrator area table
	 *
	 * The entire administrator area with contained in a table which include the admin ribbon menu
	 * in the left column and the content in the right column.  This function sets up the table and
	 * displays the admin menu in the left column.
	 */
	static function startAdminArea($vmView,$selectText = 'COM_VIRTUEMART_DRDOWN_AVA2ALL') {

		if (vRequest::getCmd ( 'format') =='pdf') return;
		if (vRequest::getCmd ( 'manage',false)) self::$backEnd=false;

		if(self::$vmAdminAreaStarted) return;
		self::$vmAdminAreaStarted = true;

		$admin = 'administrator/components/com_virtuemart/assets/css';
		$modalJs='';
		//loading defaut admin CSS
		vmJsApi::css('admin_ui',$admin);
		vmJsApi::css('admin.styles',$admin);
		vmJsApi::css('toolbar_images',$admin);
		vmJsApi::css('menu_images',$admin);
		vmJsApi::css('vtip');

		$view = vRequest::getCmd('view','virtuemart');

		if($view!='virtuemart'){
			//vmJsApi::css('chosen');
			vmJsApi::css('jquery.fancybox-1.3.4');
			//vmJsApi::css('ui/jquery.ui.all');
		}

		if($view!='virtuemart') {
			vmJsApi::addJScript('fancybox/jquery.mousewheel-3.0.4.pack',false,false);
			vmJsApi::addJScript('fancybox/jquery.easing-1.3.pack',false,false);
			vmJsApi::addJScript('fancybox/jquery.fancybox-1.3.4.pack',false,false);
			//VmJsApi::chosenDropDowns();
		}
		// Load tooltips behavior
		JHtml::_('behavior.formvalidator');
		JHtml::_('bootstrap.tooltip');
		JHtml::_('formbehavior.chosen', 'select');
		vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/jquery.coookie.js');
		vmJsApi::addJScript('/administrator/components/com_virtuemart/assets/js/vm2admin.js');

		$vm2string = "editImage: 'edit image',select_all_text: '".vmText::_('COM_VIRTUEMART_DRDOWN_SELALL')."',select_some_options_text: '".vmText::_($selectText)."'" ;
		vmJsApi::addJScript ('vm.remindTab', "
		var tip_image='".JURI::root(true)."/components/com_virtuemart/assets/js/images/vtip_arrow.png';
		var vm2string ={".$vm2string."} ;
		jQuery( function($) {

			$('dl#system-message').hide().slideDown(400);
			
			if ( $('#admin-ui-tabs').length  ) {
				$('#admin-ui-tabs').vm2admin('tabs',virtuemartcookie);
			}
			$('#content-box [title]').vm2admin('tips',tip_image);
			$('.reset-value').click( function(e){
				e.preventDefault();
				none = '';
				$(this).parent().find('.ui-autocomplete-input').val(none);
			});
		});	");

		?>
		<!--[if lt IE 9]>
		<script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
		<style type="text/css">
			.virtuemart-admin-area { display: block; }
			.virtuemart-admin-area #menu-wrapper { float: left; }
			.virtuemart-admin-area #admin-content { margin-left: 221px; }
			</script>
		<![endif]-->
		<?php if (!self::$backEnd ){
			//JToolBarHelper
			$bar = JToolbar::getInstance('toolbar');
			?><div class="toolbar-box" style="height: 84px;position: relative;"><?php echo $bar->render()?></div>
		<?php } ?>
		<?php $hideMenu = JFactory::getApplication()->input->cookie->getString('vmmenu', 'show') === 'hide' ? ' menu-collapsed': ''; ?>
		<div id="j-toggle-sidebar-wrapper">
	<div id="j-toggle-button-wrapper" class="j-toggle-button-wrapper">
		<?php echo JLayoutHelper::render('joomla.sidebars.toggle'); ?>
	</div>
	<div id="sidebar" class="sidebar">
			<?php if(!empty($vmView->langList)){ ?>
				<div class="vm-lang-list-container">
					<?php echo $vmView->langList; ?>
				</div>
			<?php } else {
				?><a href="index.php?option=com_virtuemart&amp;view=virtuemart" ><img src="<?php echo JURI::root(true).'/administrator/components/com_virtuemart/assets/images/vm_menulogo.png'?>"></a>
			<?php }
				
			AdminUIHelper::showAdminMenu($vmView);
			?>
			<div class="vm-installed-version">
				VirtueMart <?php echo VmConfig::getInstalledVersion(); ?>
			</div>
		</div>
		<div id="admin-content" class="admin-content">
		<?php
	}

	/**
	 * Close out the adminstrator area table.
	 * @author RickG, Max Milbers
	 */
	static function endAdminArea() {
		if (!self::$backEnd) return;
		self::$vmAdminAreaStarted = false;
		?>
		</div>
		</div>
		<div class="clear"></div>
		<?php
	}

	/**
	 * Admin UI Tabs
	 * Gives A Tab Based Navigation Back And Loads The Templates With A Nice Design
	 * @param $load_template = a key => value array. key = template name, value = Language File contraction
	 * @params $cookieName = choose a cookiename or leave empty if you don't want cookie tabs in this place
	 * @example 'shop' => 'COM_VIRTUEMART_ADMIN_CFG_SHOPTAB'
	 */
	static public function buildTabs($view, $load_template = array(),$cookieName='') {
			
		echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => key($load_template)));
		
		foreach ( $load_template as $tab_content => $tab_title ) {
			echo JHtml::_('bootstrap.addTab', 'myTab', $tab_content, vmText::_ ( $tab_title )); 
			echo $view->loadTemplate ( $tab_content );
			echo JHtml::_('bootstrap.endTab');
		}
		
		echo JHtml::_('bootstrap.endTabSet');
		
		$dispatcher = JDispatcher::getInstance();
		$returnValues = $dispatcher->trigger('plgVmBuildTabs', array(&$view, &$load_template));

		
	}


	/**
	 * Admin UI Tabs Imitation
	 * Gives A Tab Based Navigation Back And Loads The Templates With A Nice Design
	 * @param $return = return the start tag or the closing tag - choose 'start' or 'end'
	 * @params $language = pass the language string
	 */
	static function imitateTabs($return,$language = '') {
		if ($return == 'start') {
			echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'start'));
			echo JHtml::_('bootstrap.addTab', 'myTab', $language, vmText::_($language));
			
			;
		}
		if ($return == 'end') {
			echo JHtml::_('bootstrap.endTabSet');
		}
	}

	/**
	 * Build an array containing all the menu items.
	 *
	 * @param int $moduleId Id of the module to filter on
	 */
	static function _getAdminMenu($moduleId = 0) {
		$db = JFactory::getDBO ();
		$menuArr = array ();

		$filter [] = "jmmod.published='1'";
		$filter [] = "item.published='1'";

		if (! empty ( $moduleId )) {
			$filter [] = 'vmmod.module_id=' . ( int ) $moduleId;
		}

		$query = 'SELECT `jmmod`.`module_id`, `module_name`, `module_perms`, `id`, `name`, `link`, `depends`, `icon_class`, `view`, `task`';
		$query .= 'FROM `#__virtuemart_modules` AS jmmod
						LEFT JOIN `#__virtuemart_adminmenuentries` AS item ON `jmmod`.`module_id`=`item`.`module_id`
						WHERE  ' . implode ( ' AND ', $filter ) . '
						ORDER BY `jmmod`.`ordering`, `item`.`ordering` ';

		$db->setQuery ( $query );
		$result = $db->loadAssocList ();

		for($i = 0, $n = count ( $result ); $i < $n; $i ++) {
			$row = $result [$i];
			$menuArr [$row['module_id']] ['title'] = 'COM_VIRTUEMART_' . strtoupper ( $row['module_name'] ) . '_MOD';
			$menuArr [$row['module_id']] ['items'] [] = $row ;
		}
		return $menuArr;
	}

	/**
	 * Display the administrative ribbon menu.
	 * @todo The link should be done better
	 */
	static function showAdminMenu($vmView) {
		if(!isset(VmConfig::$installed)){
			VmConfig::$installed = false;
		}
		if(!VmConfig::$installed) return false;

		$moduleId = vRequest::getInt ( 'module_id', 0 );
		$menuItems = AdminUIHelper::_getAdminMenu ( $moduleId );
		$app = JFactory::getApplication();
		$isSite = $app->isSite(); 
				
		echo JHtml::_('bootstrap.startAccordion', 'admin-ui-menu', array("toggle" => FALSE));
		
		$modCount = 1;
		foreach ( $menuItems as $item ) {

			$html = '';
			foreach ( $item ['items'] as $link ) {
				$target='';
				if ($link ['name'] == '-') {
					// it was emtpy before
				} else {
					if (strncmp ( $link ['link'], 'http', 4 ) === 0) {
						$url = $link ['link'];
						$target='target="_blank"';
					} else {
						$url = ($link ['link'] === '') ? 'index.php?option=com_virtuemart' :$link ['link'] ;
						$url .= $link ['view'] ? "&view=" . $link ['view'] : '';
						$url .= $link ['task'] ? "&task=" . $link ['task'] : '';
						$url .= $isSite ? '&tmpl=component&manage=1':'';
						// $url .= $link['extra'] ? $link['extra'] : '';
						$url = vRequest::vmSpecialChars($url);
					}

					if ( $vmView->manager($link ['view'])
					|| $target || $link ['view']=='about' || $link ['view']=='virtuemart') {
						$html .= '
					<li>
						<a href="'.$url.'" '.$target.'>
							<span class="vmicon-wrapper"><span class="'.$link ['icon_class'].'"></span></span>
							<span class="menu-subtitle">'. vmText::_ ( $link ['name'] ).'</span>
						</a>
					</li>';
					}
				}
			}
			if(!empty($html)){
				echo JHtml::_('bootstrap.addSlide', 'admin-ui-menu', vmText::_ ( $item ['title'] ), 'slide'.$modCount.'_id'); ?>
					<ul>
						<?php echo $html ?>
					</ul>
				<?php echo JHtml::_('bootstrap.endSlide'); 
				$modCount ++;
			}
		}
		echo JHtml::_('bootstrap.endAccordion');

	}

}
