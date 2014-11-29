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
 * Blog listing controller class
 *
 * @since  1.0
 */
class BlogController extends DefaultController
{
	/**
	 * Method to initialize the model object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function initializeModel()
	{
		$model = '\\Extensions\\Articles\\Model\\ListsModel';

		$object = $this->getContainer()->buildObject($model);
		$object->setState($this->initializeModelState($object));

		$this->getContainer()->set($model, $object)->alias('Joomla\\Model\\ModelInterface', $model);
	}

	/**
	 * Method to initialize the state object for the model
	 *
	 * @param   \Joomla\Model\ModelInterface  $model  The model object
	 *
	 * @return  Registry
	 *
	 * @since   1.0
	 */
	protected function initializeModelState(\Joomla\Model\ModelInterface $model)
	{
		$state = $model->getState();
		$state->set('category.alias', 'blog');

		return $state;
	}
}
