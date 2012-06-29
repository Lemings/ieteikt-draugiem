<?php
/**
 * @version     $Id: ieteiktDraugiem.php 08.03.2011 lemings $
 * @package     IeteiktDraugiem.
 * @subpackage  Content
 * @copyright   Copyright (C) 2012 Edgars Piruška. All rights reserved.
 * @license     GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgContentIeteiktDraugiem extends JPlugin
{

	/**
	 * Pievieno saiti rakstiem draugiem.lv ieteikšanas funkcijai
	 *
	 * @param   string  $context     context
	 * @param   object  &$article    article object
	 * @param   object  &$params     article params
	 * @param   int     $limitstart  start index
	 *
	 * @return null
	 */
	public function onContentPrepare($context, &$article, &$params, $limitstart)
	{
		if ($context == 'com_content.article' || $context == 'com_k2.item')
		{
			$menus = $this->params->get('menusid', array());
			if (!empty($menus) && !in_array(JFactory::getApplication()->getMenu()->getDefault()->id, $menus))
			{
				return;
			}

			$prefix					= $this->params->get('prefix');
			$ieteiktDraugiemPrefix 	= (!empty($prefix)) ? '&amp;titlePrefix=' . urlencode($prefix) : "";

			$group = $this->params->get('group', 'both');
			if (($group == 'both' || $group == 'com_content') && $context = 'com_content.article')
			{
				$categories = $params->get('catsid', array());
				if (!empty($categories) && !in_array($article->catid, $categories))
				{
					return;
				}

				$articleSlug 	= (!empty($article->slug)) ? $article->slug : $article->id;
				$catSlug		= (!empty($article->catslug)) ? $article->catslug : $article->catid;
				$link = JURI::base(false) . JRoute::_(ContentHelperRoute::getArticleRoute($articleSlug, $catSlug, false, -1));
				$link = str_replace('%26amp%3B', '%26', urlencode($link));
				$article->text .= '<div class="ieteiktDraugiem"><iframe height="20" width="84" frameborder="0"
				src="http://www.draugiem.lv/say/ext/like.php?title=' . urlencode($article->title) . '&amp;url=' .
				$link . $ieteiktDraugiemPrefix . '"></iframe></div>';
			}
			// K2 rakstiem
			elseif (($group = 'both' || $group = 'com_k2') && $context = 'com_k2.item')
			{
				$k2Categories = $params->get('k2catsid', array());
				if (!empty($k2Categories) && !in_array($article->catid, $k2Categories))
				{
					return;
				}
				$articleSlug 	= (!empty($article->slug)) ? $article->slug : $article->id;
				$catSlug		= (!empty($article->catslug)) ? $article->catslug : $article->catid;
				$link = JURI::base(false) . JRoute::_(K2HelperRoute::getItemRoute($articleSlug, $catSlug));
				$link = str_replace('%26amp%3B', '%26', urlencode($link));
				return '<div class="ieteiktDraugiem"><iframe height="20" width="84" frameborder="0"
				src="http://www.draugiem.lv/say/ext/like.php?title=' . urlencode($article->title) . '&amp;url=' .
				$link . $ieteiktDraugiemPrefix . '"></iframe></div>';
			}
		}
	}
}
