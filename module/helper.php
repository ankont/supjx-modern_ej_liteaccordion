<?php
/**
 * @package    Joomla.Module
 * @subpackage mod_ej_liteaccordion
 * @copyright  Copyright (C) 2006 - 2025 Andreas Kontarinis & Element-J.com. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.php
 * @version    2.0.0
 * @since      2025-06-02
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;

abstract class modEjLiteAccordionHelper
{
	public static function getList(&$params)
	{
		$app = Factory::getApplication();
        $fldCount = (int) $params->get('ejla_count', []);
        $fldCatID = $params->get('ejla_catid', []);
		$fldOrdering = strtok($params->get('ejla_ordering', 'a.publish_up'),' ');
		$fldOrderDir = strtok(' ');
		if (!$fldOrderDir) {
			$fldOrderDir = 'DESC';
		}
		// Get an instance of the generic articles model
		$model = $app->bootComponent('com_content')
			->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);

		// Set application parameters in model
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', $fldCount);

		$model->setState('filter.published', 1);

		/*
		 * Old select statement from mod_articles_latest.
		 * Retained for reference, but the new select statement below is preferred.
		 */
		/*
		$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.title_alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
			' a.modified, a.modified_by,a.publish_up, a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
			' a.hits, a.featured,' .
			' LENGTH(a.fulltext) AS readmore');
		*/

		// New select statement for article fields relevant to the module
		$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
			' a.modified, a.modified_by, a.publish_up, a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
			' a.hits, a.featured' );

		// Access filter
		$access = !ComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$model->setState('filter.category_id', $fldCatID);

		// Filter by language
		$model->setState('filter.language',$app->getLanguageFilter());

		// Set ordering
		$model->setState('list.ordering', $fldOrdering);
		if (trim($fldOrdering) == 'rand()') {
			$model->setState('list.direction', '');
		} else {
			$model->setState('list.direction', $fldOrderDir);
		}

		// Retrieve Content
		$items = $model->getItems();

		foreach ($items as &$item) // Non-breaking space removed here
		{
			$item->readmore = (trim($item->fulltext) != '');
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid.':'.$item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE'); // This string might need to be defined in your module's language file if not already in Joomla core
			}
			else {
				$item->link = Route::_('index.php?option=com_user&view=login');
				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE_REGISTER'); // This string might need to be defined in your module's language file if not already in Joomla core
			}

			// Prepare introtext for display (e.g., plugin parsing)
			$item->introtext = HTMLHelper::_('content.prepare', $item->introtext);

			// If introtext is empty after preparation, try to use fulltext
			if (trim(strip_tags($item->introtext)) === '') {
				$item->introtext = HTMLHelper::_('content.prepare', $item->fulltext);
			}

			// Original comment about image removal - retained for reference
			/*
			if (!$params->get('image')) {
				$item->introtext = preg_replace('/<img[^>]*>/', '', $item->introtext);
			}
			*/

			// Trigger content plugins for 'after display title' and 'before display content' events
			$results = $app->triggerEvent('onContentAfterDisplay', array('com_content.article', &$item, &$params, 1));
			$item->afterDisplayTitle = trim(implode("\n", $results));

			$results = $app->triggerEvent('onContentBeforeDisplay', array('com_content.article', &$item, &$params, 1));
			$item->beforeDisplayContent = trim(implode("\n", $results));
		}

		return $items;
	}
}