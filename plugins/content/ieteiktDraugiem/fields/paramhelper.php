<?php
/**
 * ------------------------------------------------------------------------
* Param helper based on JA Comment plugin japaramhelper
* ------------------------------------------------------------------------
* Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
* @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
* Author: J.O.O.M Solutions Co., Ltd
* Websites: http://www.joomlart.com - http://www.joomlancers.com
* ------------------------------------------------------------------------
*/

// Ensure this file is being included by a parent file
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Radio List Element
 *
 * @since      Class available since Release 1.2.0
 */
class JFormFieldParamhelper extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'Paramhelper';

	protected function getInput()
	{
		if (!defined('_PARAM_HELPER'))
		{
			define('_PARAM_HELPER', 1);
			JHtml::_('script', '/plugins/content/ieteiktDraugiem/assets/js/japaramhelper.js', true, false);
		}
		$func 	= (string) $this->element['function'] ? (string) $this->element['function'] : '';
		$value 	= $this->value ? $this->value : (string) $this->element['default'];

		if (substr($func, 0, 1) == '@')
		{
			$func = substr($func, 1);
			if (method_exists($this, $func))
			{
				return $this->$func();
			}
		} else {
			$subtype = (isset($this->element['subtype'])) ? trim($this->element['subtype']) : '';
			if (method_exists($this, $subtype))
			{
				return $this->$subtype ();
			}
		}
		return;
	}


	function getLabel()
	{
		$func 	= (string) $this->element['function'] ? (string) $this->element['function'] : '';
		if (substr($func, 0, 1) == '@' || !isset( $this->label ) || !isset($this->label))
		{
			return;
		}
		else
		{
			return parent::getLabel();
		}
	}

	/**
	 * render title: name="@title"
	 */
	function title()
	{
		$_title			= (string) $this->element['label'];
		$_description	= $this->description;
		$_url			= ( isset( $this->element['url'] ) ) ? (string)$this->element['url'] : '';
		$class			= ( isset( $this->element['class'] ) ) ? (string)$this->element['class'] : '';
		$level			= ( isset( $this->element['level'] ) ) ? (string)$this->element['level'] : '';
		$group			= ( isset( $this->element['group'] ) ) ? (string)$this->element['group'] : '';
		$group			= $group ? "id='params$group-group'":"";
		if ($_title)
		{
			$_title = html_entity_decode(JText::_($_title));
		}

		if ($_description)
		{
			$_description = html_entity_decode(JText::_($_description));
		}
		if ($_url)
		{
			$_url = " <a target='_blank' href='{$_url}' >[" . html_entity_decode(JText::_("DEMO")) . "]</a> ";
		}

		$regionID = time() + rand();

		$class_name = trim(str_replace(" ", "", strtolower($_title)));

		if ($level==1)
		{
			$html = '
			<h4 rel="' . $level . '" class="block-head block-head-' . $class_name . ' open ' . $class . ' " ' . $group . ' id="' . $regionID . '">
			<span class="block-setting" >' . $_title . $_url . '</span>
			<span class="icon-help editlinktip hasTip" title="' . htmlentities($_description) . '">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<a class="toggle-btn open" title="' . JText::_('EXPAND_ALL').'" onclick="showRegion(\''.$regionID.'\', \'level'.$level.'\'); return false;">'.JText::_('EXPAND_ALL').'</a>
			<a class="toggle-btn close" title="'.JText::_('COLLAPSE_ALL').'" onclick="hideRegion(\''.$regionID.'\', \'level'.$level.'\'); return false;">'.JText::_('COLLAPSE_ALL').'</a>
			</h4>';
		}
		else {
			$html = '
			<h4 rel="'.$level.'" class="block-head block-head-'.$class_name.' open '.$class.' " '.$group.' id="'.$regionID.'">
			<span class="block-setting" >'.$_title.$_url.'</span>
			<span class="icon-help editlinktip hasTip" title="'.htmlentities($_description).'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<a class="toggle-btn" title="'.JText::_('CLICK_HERE_TO_EXPAND_OR_COLLAPSE').'" onclick="showHideRegion(\''.$regionID.'\', \'level'.$level.'\'); return false;">open</a>
			</h4>';
		}
		//<div class="block-des '.$class.'"  id="desc-'.$regionID.'">'.$_description.'</div>';

		return $html;
	}

	/**
	 * Subtype - Checkbox: subtype="checkbox"
	 */
	function checkbox (){
		$k = 0;
		$html = "";

		$cols = intval($this->element['cols']);
		if($cols == 0) $cols = 1;
		$width = floor(100/$cols);
		$style = ' style="width:'.$width.'%;"';
		if($this->element->children()){
			foreach ($this->element->children() as $option)
			{
				$group = isset($option['group'])?intval($option['group']):0;
				$odesc	= isset($option['description'])?JText::_($option['description']):'';
				$otext	= JText::_(trim((string) $option));

				$tooltip	= addslashes(htmlspecialchars($odesc, ENT_QUOTES, 'UTF-8'));
				$titletip		= addslashes(htmlspecialchars($otext, ENT_QUOTES, 'UTF-8'));

				if($titletip) {
					$titletip = $titletip.'::';
				}

				if($group) {
					$html .= "\n\t<div class=\"group_title\"><span class=\"hasTip\" title=\"{$titletip}{$tooltip}\">$otext</span></div>";
				} else {


					$oval	= $option['value'];
					$children	= $option['children'];
					$alt = ($children) ? ' alt="'.$children.'"' : '';
					$extra	 = '';

					if (is_array( $this->value ))
					{
						foreach ($value as $val)
						{
							$val2 = is_object( $val ) ? $val->$key : $val;
							if ($oval == $val2)
							{
								$extra .= ' checked="checked"';
								break;
							}
						}
					} else {
						$extra .= ( (string)$oval == (string)$this->value  ? ' checked="checked"' : '' );
					}

					$html .= "\n\t<div class=\"group_item\" $style>";
					$html .= "\n\t<input type=\"checkbox\" name=\"{$this->name}[]\" id=\"{$this->id}{$k}\" value=\"$oval\"$extra $alt />";
					$html .= "\n\t<label id=\"{$this->id}{$k}-label\" class=\"hasTip\" title=\"{$titletip}{$tooltip}\" for=\"{$this->id}{$k}\">$otext</label>";
					$html .= "\n\t</div>";

					$k++;
				}
			}
		}

		return $html;
	}

	/**
	 * render js to control setting form.
	 */
	function group()
	{
		preg_match_all('/jform\\[([^\]]*)\\]/', $this->name, $matches);

		?>
<script type="text/javascript">
<?php foreach ($this->element->children() as $option) {?>
<?php $str_els = trim((string) $option); ?>
<?php $str_els = str_replace("\n", '', $str_els) ?>
<?php $hideRow = isset($option['hideRow'])?''.$option['hideRow'].'':1;?>
japh_addgroup ('<?php echo $option['for']; ?>', { val: '<?php echo $option['value']; ?>', els_str: '<?php echo $str_els?>', group:'jform[<?php echo @$matches[1][0]?>]', hideRow: <?php echo $hideRow?>});
<?php };?>
</script>
<?php
return ;
}
}