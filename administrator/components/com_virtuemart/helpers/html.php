<?php
/**
 * HTML helper class
 *
 * This class was developed to provide some standard HTML functions.
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author RickG
 * @copyright Copyright (c) 2004-2008 Soeren Eberhardt-Biermann, 2009 VirtueMart Team. All rights reserved.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

use Joomla\Utilities\ArrayHelper;

/**
 * HTML Helper
 *
 * @package VirtueMart
 * @subpackage Helpers
 * @author RickG
 */
class VmHtml{

	/**
	 * Default values for options. Organized by option group.
	 *
	 * @var     array
	 * @since   11.1
	 */
	static protected $_optionDefaults = array(
		'option' => array('option.attr' => null, 'option.disable' => 'disable', 'option.id' => null, 'option.key' => 'value',
			'option.key.toHtml' => true, 'option.label' => null, 'option.label.toHtml' => true, 'option.text' => 'text',
			'option.text.toHtml' => true));

	static protected $_usedId = array();

	static function ensureUniqueId($id){

		if(isset(self::$_usedId[$id])){
			$c = 1;
			while(isset(self::$_usedId[$id.$c])){
				$c++;
			}
			$id = $id.$c;
		}
		self::$_usedId[$id] = 1;
		return $id;
	}

	/**
	 * Converts all special chars to html entities
	 *
	 * @param string $string
	 * @param string $quote_style
	 * @param boolean $only_special_chars Only Convert Some Special Chars ? ( <, >, &, ... )
	 * @return string
	 */
	static function shopMakeHtmlSafe( $string, $quote_style='ENT_QUOTES', $use_entities=false ) {

		if( defined( $quote_style )) {
			$quote_style = constant($quote_style);
		}
		if( $use_entities ) {
			$string = @htmlentities( $string, constant($quote_style), 'UTF-8' );
		} else {
			$string = @htmlspecialchars( $string, $quote_style, 'UTF-8' );
		}
		return $string;
	}

	/**
	 * Returns the Tooltip for the given Label
	 *
	 * @param string $label : Text Label
	 * @return string : Text Tip
	 * @since 3.0.18
	 */
	static function getTooltip($label) {
		$help='';
		$lang =JFactory::getLanguage();
		
		if($lang->hasKey($label.'_TIP')){
			$labelHint = vmText::_($label.'_TIP');
			$help = 'title data-content="'.$labelHint.'"' ;

		} //Fallback
		else if($lang->hasKey($label.'_EXPLAIN')){
			$labelHint = vmText::_($label.'_EXPLAIN');
			$help = 'title data-content="'.$labelHint.'"' ;
		} 
		return $help;
	}
	
	/**
	 * Returns the Bootstrap Row starting div and the Label for the Row
	 *
	 * @param string $label : Text Label
	 * @param int $id : field id
	 * @return string : HTML
	 * @since 3.0.18
	 */
	static function getRowLabel($label,$id) {
		
		$labelText = vmText::_($label);
		$help = VmHtml::getTooltip($label);
		$class = '';
		if (!$help == '') {
			$class ="hasPopover";
		}
		$html = '<div class="control-group">';
		$html .= '<div class="control-label">';
							
		$html .= '<label id="'.$id.'" for="'.$id.'" class="'.$class.'" '.$help.'  data-original-title="'.$labelText.'">'.$labelText.'</label>'; 
		$html .= '</div><div class="controls">';
		return $html;
	}
    /**
     * Generate HTML code for a row using VmHTML function
     * works also with shopfunctions, for example
	 * $html .= VmHTML::row (array('ShopFunctions', 'renderShopperGroupList'),
	 * 			'VMCUSTOM_BUYER_GROUP_SHOPPER', $field->shopper_groups, TRUE, 'custom_param['.$row.'][shopper_groups][]', ' ');
	 *
     * @func string  : function to call
     * @label string : Text Label
     * @args array : arguments
     * @return string: HTML code for row table
     */
    static function row($func,$label){
		$VmHTML="VmHtml";
		if (!is_array($func)) {
			$func = array($VmHTML, $func);
		}
		$passedArgs = func_get_args();
		array_shift( $passedArgs );//remove function
			$args = array();
			foreach ($passedArgs as $k => $v) {
			    $args[] = &$passedArgs[$k];
			}
		
		$html = "";
		if($func[1]=='radioList'){
			$html = VmHtml::getRowLabel($label, $name);
			$html .= '<fieldset class="checkboxes">';
		}

		$html .= call_user_func_array($func, $args);
		if($func[1]=='radioList'){
			$html .= '</fieldset>';
			$html .= '</div></div>';
		}
	
		return $html ;

	}
	/* simple value display */
	static function value( $value ){
		$lang =JFactory::getLanguage();
		return $lang->hasKey($value) ? vmText::_($value) : $value;
	}
	/**
	 * The sense is unclear !
	 * @deprecated
	 * @param $value
	 * @return mixed
	 */
	static function raw( $dummy, $value ){
		return $value;
	}
    /**
     * Generate HTML code for a checkbox
     *
     * @param string Name for the checkbox
     * @param mixed Current value of the checkbox
     * @param mixed Value to assign when checkbox is checked
     * @param mixed Value to assign when checkbox is not checked
     * @return string HTML code for checkbox
     */
    static function checkbox($label, $name, $value, $checkedValue=1, $uncheckedValue=0, $extraAttribs = '') {
	    
	    $html = VmHtml::getRowLabel($label, $name);
	    
		if (!$label ='' ) {
			$label = 'id="' . $label.'"';
		}

		if ($value == $checkedValue) {
			$checked = 'checked="checked"';
		}
		else {
			$checked = '';
		}

		$html .= '<input type="hidden" name="' . $name . '" value="' . $uncheckedValue . '" />';
		$html .= '<input '.$extraAttribs.' ' . $label . ' type="checkbox" name="' . $name . '" value="' . $checkedValue . '" ' . $checked . ' />';
		$html .= '</div></div>';
		return $html;
    }

	/**
	 *
	 * @author Patrick Kohl
	 * @param array $options( value & text)
	 * @param string $name option name
	 * @param string $defaut defaut value
	 * @param string $key option value
	 * @param string $text option text
	 * @param boolean $zero add  a '0' value in the option
	 * return a select list
	 */
	static function select($label, $name, $options, $default = '0',$attrib = "onchange='submit();'",$key ='value' ,$text ='text', $zero=true, $chosenDropDowns=true,$translate=true){
		$html = VmHtml::getRowLabel($label, $name);
		
		if ($zero==true) {
			$option  = array($key =>"0", $text => vmText::_('COM_VIRTUEMART_LIST_EMPTY_OPTION'));
			$options = array_merge(array($option), $options);
		}
		if ($chosenDropDowns) {
			vmJsApi::chosenDropDowns();
			$attrib .= ' class="vm-chzn-select"';

		}
		$html .= JHTML::_('select.genericlist', $options, $name, $attrib, $key, $text, $default, $label, $translate);
		$html .= '</div></div>';
		return $html;
	}

	/**
	 * Generates an HTML selection list.
	 *                               to be selected, based on the option key values.
	 * @param   string   $optKey     The name of the object variable for the option value. If
	 *                               set to null, the index of the value array is used.
	 * @param   string   $optText    The name of the object variable for the option text.
	 * @param   mixed    $selected   The key that is selected (accepts an array or a string).
	 * @param   mixed    $idtag      Value of the field id or null by default
	 * @param   boolean  $translate  True to translate
	 *
	 * @return  string  HTML for the select list.
	 *
	 * @since   11.1
	 */
	static function genericlist($label, $data, $name, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false)
	{
		$html = VmHtml::getRowLabel($label, $name);
		
		$html .= JHTML::_('select.genericlist', $data, $name, $attribs, $optKey, $optText, $selected, $label, $translate);

		$html .= '</div></div>';
		return  $html;
		
			}

	/**
	 * Prints an HTML dropdown box named $name using $arr to
	 * load the drop down.  If $value is in $arr, then $value
	 * will be the selected option in the dropdown.
	 * @author gday
	 * @author soeren
	 *
	 * @param string $name The name of the select element
	 * @param string $value The pre-selected value
	 * @param array $arr The array containing $key and $val
	 * @param int $size The size of the select element
	 * @param string $multiple use "multiple=\"multiple\" to have a multiple choice select list
	 * @param string $extra More attributes when needed
	 * @return string HTML drop-down list
	 */
	static function selectList($label, $name, $value, $arrIn, $size=1, $multiple="", $extra="", $data_placeholder='') {

		$html = '';
		if( empty( $arrIn ) ) {
			$arr = array();
		} else {
			if(!is_array($arrIn)){
	        	 $arr=array($arrIn);
	        } else {
	        	 $arr=$arrIn;
	        }
		}
		if (!empty($data_placeholder)) {
			$data_placeholder='data-placeholder="'.vmText::_($data_placeholder).'"';
		}

		$html = '<select class="inputbox" id="'.$name.'" name="'.$name.'" size="'.$size.'" '.$multiple.' '.$extra.' '.$data_placeholder.' >';

		while (list($key, $val) = each($arr)) {
//		foreach ($arr as $key=>$val){
			$selected = "";
			if( is_array( $value )) {
				if( in_array( $key, $value )) {
					$selected = 'selected="selected"';
				}
			}
			else {
				if(strtolower($value) == strtolower($key) ) {
					$selected = 'selected="selected"';
				}
			}

			$html .= '<option value="'.$key.'" '.$selected.'>'.self::shopMakeHtmlSafe($val);
			$html .= '</option>';

		}

		$html .= '</select>';

		return $html;
	}

	/**
	 * @author Joomla
	 */
	static function color($label, $name, $value) {

		$color = strtolower($value);

		if (!$color || in_array($color, array('none', 'transparent'))) {
			$color = 'none';
		} elseif ($color['0'] != '#') {
			$color = '#' . $color;
		}

		// Including fallback code for HTML5 non supported browsers.
		vmJsApi::jQuery();
		
		$class = ' class="minicolors"';

		JHtml::_('behavior.colorpicker');

		return '<input type="text" name="' . $name . '" ' . ' value="'
		. htmlspecialchars($color, ENT_COMPAT, 'UTF-8') . '"' . $class
		. '/>';

	}



	/**
	 * Generates an HTML radio list.
	 *
	 * @param   array    $data       An array of objects
	 * @param   string   $name       The value of the HTML name attribute
	 * @param   string   $attribs    Additional HTML attributes for the `<select>` tag
	 * @param   mixed    $optKey     The key that is selected
	 * @param   string   $optText    The name of the object variable for the option value
	 * @param   string   $selected   The name of the object variable for the option text
	 * @param   boolean  $idtag      Value of the field id or null by default
	 * @param   boolean  $translate  True if options will be translated
	 *
	 * @return  string  HTML for the select list
	 *
	 * @since   1.5
	 */
	static function radiolist($label, $name, $data, $attribs = null, $optKey = 'value', $optText = 'text', $selected = null, $idtag = false, $translate = false)
	{

		if (is_array($attribs))
		{
			$attribs = ArrayHelper::toString($attribs);
		}

		$id_text = $idtag ? $idtag : $name;

		$html = '';
		
		if( empty( $data ) ) {
			$data = array();
		}
		foreach ($data as $obj)
		{
			$k = $obj->$optKey;
			$t = $translate ? JText::_($obj->$optText) : $obj->$optText;
			$id = (isset($obj->id) ? $obj->id : null);

			$extra = '';
			$id = $id ? $obj->id : $id_text . $k;

			if (is_array($selected))
			{
				foreach ($selected as $val)
				{
					$k2 = is_object($val) ? $val->$optKey : $val;

					if ($k == $k2)
					{
						$extra .= ' selected="selected" ';
						break;
					}
				}
			}
			else
			{
				$extra .= ((string) $k == (string) $selected ? ' checked="checked" ' : '');
			}

			$html .= "\n\t" . '<label for="' . $id . '" id="' . $id . '-lbl" class="radio">';
			$html .= "\n\t\n\t" . '<input type="radio" name="' . $name . '" id="' . $id . '" value="' . $k . '" ' . $extra
				. $attribs . ' />' . $t;
			$html .= "\n\t" . '</label>';
		}

		$html .= "\n";

		return $html;
	}

	/**
	 * Creates radio List
	 * @param array $radios
	 * @param string $name
	 * @param string $default
	 * @return string
	 */
	static function radio($label, $name, $radios, $default,$key='value',$text='text') {
		return '<fieldset class="radio">'.JHtml::_('select.radiolist', $radios, $name, '', $key, $text, $default).'</fieldset>';
	}
	/**
	 * Creating rows with boolean list
	 *
	 * @author Patrick Kohl
	 * @param string $label
	 * @param string $name
	 * @param string $value
	 *
	 */
	public static function booleanlist ( $label, $name, $selected, $class='class="inputbox"', $yes = 'JYES', $no = 'JNO', $id = false){
		
		$html = VmHtml::getRowLabel($label, $name);
		
		$value = array(JHtml::_('select.option', '0', JText::_($no)), JHtml::_('select.option', '1', JText::_($yes)));
		
		$html .= '<fieldset id="'.$name.'" class="btn-group btn-group-yesno radio">';
		$html .= VmHtml::radioList($label, $name, $value, $class, 'value', 'text', (int) $selected, $id );
		$html .= '</fieldset>';
		$html .= '</div></div>';
		return  $html;
	}

	/**
	 * Creating rows with input fields
	 *
	 * @param string $text
	 * @param string $name
	 * @param string $value
	 */
	public static function input($label, $name, $value,$class='class="inputbox"',$readonly='',$size='37',$maxlength='255',$more=''){
		$html = VmHtml::getRowLabel($label, $name);
		$html .= '<input type="text" '.$readonly.' '.$class.' id="'.$name.'" name="'.$name.'" size="'.$size.'" maxlength="'.$maxlength.'" value="'.($value).'" />'.$more;
		$html .= '</div></div>';
		return  $html;

	}

	/**
	 * Creating rows with input fields
	 *
	 * @author Patrick Kohl
	 * @param string $text
	 * @param string $name
	 * @param string $value
	 */
	public static function textarea($label, $name, $value, $class='class="inputbox"',$cols='100',$rows="4"){
		$html = VmHtml::getRowLabel($label, $name);
		$html .= '<textarea '.$class.' id="'.$name.'" name="'.$name.'" cols="'.$cols.'" rows="'.$rows.'">'.$value.'</textarea >';
		$html .= '</div></div>';
		return  $html;

	}
	/**
	 * render editor code
	 *
	 * @author Patrick Kohl
	 * @param string $text
	 * @param string $name
	 * @param string $value
	 */
	public static function editor($label, $name,$value,$size='100%',$height='300',$hide = array('pagebreak', 'readmore')){
		$editor =JFactory::getEditor();
		return $editor->display($name, $value, $size, $height, null, null ,$hide )  ;
	}


	/**
	 * renders the hidden input
	 * @author Max Milbers
	 */
	public static function inputHidden($values){
		$html='';
		foreach($values as $k=>$v){
			$html .= '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
		}
		return $html;
	}

	/**
	* @author Patrick Kohl
	* @var $type type of regular Expression to validate
	* $type can be I integer, F Float, A date, M, time, T text, L link, U url, P phone
	*@bool $required field is required
	*@Int $min minimum of char
	*@Int $max max of char
	*@var $match original ID field to compare with this such as Email, passsword
	*@ Return $html class for validate javascript
	**/
	public static function validate($type='',$required=true, $min=null,$max=null,$match=null) {

		if ($required) $validTxt = 'required';
		else $validTxt = 'optional';
		if (isset($min)) $validTxt .= ',minSize['.$min.']';
		if (isset($max)) $validTxt .= ',maxSize['.$max.']';
		static $validateID=0 ;
		$validateID++;
		if ($type=='S' ) return 'id="validate'.$validateID.'" class="validate[required,minSize[2],maxSize[255]]"';
		$validate = array ( 'I'=>'onlyNumberSp', 'F'=>'number','D'=>'dateTime','A'=>'date','M'=>'time','T'=>'Text','L'=>'link','U'=>'url','P'=>'phone');
		if (isset ($validate[$type])) $validTxt .= ',custom['.$validate[$type].']';
		$html ='id="validate'.$validateID.'" class="validate['.$validTxt.']"';

		return $html ;
	}

}