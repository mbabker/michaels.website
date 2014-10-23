<?php
/**
 * Articles extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Articles\Controller;

use BabDev\Website\Controller\DefaultController;

use Joomla\Registry\Registry;

/**
 * Single article controller class
 *
 * @since  1.0
 */
class ArticleController extends DefaultController
{
	/**
	 * Method to initialize the controller object, called after the parent constructor has been processed
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initializeController()
	{
		parent::initializeController();

		$this->getInput()->set('layout', 'display');
	}

	/**
	 * Method to initialize the state object for the model
	 *
	 * @return  Registry
	 *
	 * @since   1.0
	 */
	protected function initializeModelState()
	{
		$state = new Registry;
		$state->set('category.alias', 'blog');
		$state->set('article.alias', $this->getInput()->getString('alias', ''));

		return $state;
	}
}
