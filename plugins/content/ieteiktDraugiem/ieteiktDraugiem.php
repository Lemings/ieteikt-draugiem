<?php
/**
 * @version             $Id: ieteiktDraugiem.php 08.03.2011 lemings $
 * @package             ieteiktDraugiem.
 * @subpackage  Content
 * @copyright   Copyright (C) 2012 Edgars Piruška. All rights reserved.
 * @license             GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgContentIeteiktDraugiem extends JPlugin {

	/**
 * Constructor
 *
 * For php4 compatability we must not use the __constructor as a constructor for plugins
 * because func_get_args ( void ) returns a copy of all passed arguments NOT references.
 * This causes problems with cross-referencing necessary for the observer design pattern.
 *
 * @param object $subject The object to observe
 * @param object $params  The object that holds the plugin parameters
 * @since 1.5
 */
	function plgContentIeteiktDraugiem( &$subject, $params )
	{
		parent::__construct( $subject, $params );
	}

	//'com_content.article', &$item, &$this->params, $offset
	function onContentPrepare( $context, &$article, &$params, $limitstart )
	{

		if ($context != 'com_content.article')
		{
			return;
		}
		$uri = JFactory::getURI();
		$prefix	= $this->params->get('prefix');
		$ieteiktDraugiemPrefix = (!empty($prefix)) ? '&amp;titlePrefix='.urlencode($prefix) : "";

		$link = str_replace('%26amp%3B', '%26', urlencode(JRoute::_(ContentHelperRoute::getArticleRoute((!empty($article->slug))? $article->slug :
			$article->id, (!empty($article->catslug)) ? $article->catslug : $article->catid ),false,-1)));
		$article->text .='<div class="ieteiktDraugiem"><iframe height="20" width="84" frameborder="0"
		src="http://www.draugiem.lv/say/ext/like.php?title='.urlencode($article->title).'&amp;url='.$link.$ieteiktDraugiemPrefix.'"></iframe></div>';

	}
}
