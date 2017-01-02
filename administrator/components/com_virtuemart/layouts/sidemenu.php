<?php 

defined('JPATH_BASE') or die;

JHtml::_('jquery.framework');
JHtmlBehavior::core();

JFactory::getDocument()->addScriptDeclaration('
	jQuery(document).ready(function($)
	{
		if (window.toggleSidebar)
		{
			toggleSidebar(true);
		}
		else
		{
			$("#j-toggle-sidebar-header").css("display", "none");
			$("#j-toggle-button-wrapper").css("display", "none");
		}
	});
');

?>

<div id="j-toggle-sidebar-wrapper">
	<div id="j-toggle-button-wrapper" class="j-toggle-button-wrapper">
		<?php echo JLayoutHelper::render('joomla.sidebars.toggle'); ?>
	</div>
	<div id="sidebar" class="sidebar">
	<?php
		
		$moduleId = vRequest::getInt ( 'module_id', 0 );
		
		$menuItems = _getAdminMenu ( $moduleId ); 
				
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
						
						$url = vRequest::vmSpecialChars($url);
					}

					
						$html .= '
					<li>
						<a href="'.$url.'" '.$target.'>
							<span class="vmicon-wrapper"><span class="'.$link ['icon_class'].'"></span></span>
							<span class="menu-subtitle">'. vmText::_ ( $link ['name'] ).'</span>
						</a>
					</li>';
					
				}
			}
			if(!empty($html)){
				echo JHtml::_('bootstrap.addSlide', 'admin-ui-menu', vmText::_ ( $item ['title'] ), 'slide'.$modCount.'_id'); ?>
					<ul id="submenu" class="nav nav-list">
						<?php echo $html ?>
					</ul>
				<?php echo JHtml::_('bootstrap.endSlide'); 
				$modCount ++;
			}
		}
		echo JHtml::_('bootstrap.endAccordion'); ?>
		
		</div>
	</div>
	<div id="j-toggle-sidebar"></div>
</div>

<?php

	/**
	 * Build an array containing all the menu items.
	 *
	 * @param int $moduleId Id of the module to filter on
	 */
	function _getAdminMenu($moduleId = 0) {
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
