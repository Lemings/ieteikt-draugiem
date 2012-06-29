<?php
/**
 * ------------------------------------------------------------------------
 * JA Comment plugin for Joomla 1.7.x
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');

class JFormFieldK2category extends JFormField
{
    /**
     * @access private
     */
	protected	$_name = 'k2category';

	protected function getInput() {
		$db = &JFactory::getDBO();

		$controlId = str_replace('[', '', $this->name);
		$controlId = str_replace(']', '', $controlId);

		if (! $this->checkComponent('com_k2')) {
			return '<input type="hidden" name="' . $this->name . '[]" id="' . $controlId . '" value="" /> <span style="color:red; float:left">' . JText::_("K2_COMPONENT_IS_NOT_INSTALLED") . '</span>';
		}
		
		$query = 'SELECT m.* FROM #__k2_categories m WHERE published=1 AND trash = 0 ORDER BY parent, ordering';
		$db->setQuery( $query );
		$mitems = $db->loadObjectList();
		$children = array();
		if ($mitems) {
			foreach ( $mitems as $v ) {
				$v->parent_id = $v->parent;
				$v->title = $v->name;
				if(isset($children[$v->parent])) {
					$children[$v->parent][] = $v;
				} else {
					$children[$v->parent] = array($v);
				}
			}
		} else {
			return '<input type="hidden" name="' . $this->name . '[]" id="' . $controlId . '" value="" /> <span style="color:red; float:left">' . JText::_("PLEASE_INSERT_K2_CATEGORY") . '</span>';
		}
		
		$list = JHTML::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0 );
		$mitems = array();
		// $mitems[] = JHTML::_('select.option', '', '-- '.JText::_('ALL_CATEGORIES'));
		foreach ( $list as $item ) {
			$item->treename = str_replace('- ', '', $item->treename);
			$item->treename = str_replace('&#160;&#160;', '- ', $item->treename);
			$mitems[] = JHTML::_('select.option',  $item->id, $item->treename);
		}
		
		$value = $this->value ? $this->value : (string)$this->element['default'];
		
		$output= JHTML::_('select.genericlist',  $mitems, $this->name . '[]', 
						'multiple="multiple" size="10"', 'value', 'text', $value );
		return $output;
	}
	
	protected function checkComponent($component)
	{
		$db = JFactory::getDBO();
		$query = " SELECT Count(*) FROM #__extensions as c WHERE c.element = '$component' AND c.enabled = 1";
		$db->setQuery($query);
		return $db->loadResult();
	}
}