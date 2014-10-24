<?php
/**
 * Articles extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Articles\Controller;

use BabDev\Website\Controller\AdminController as BaseAdminController;
use BabDev\Website\Factory;

use Joomla\Filter\InputFilter;
use Joomla\Registry\Registry;

/**
 * Base controller for the articles extension
 *
 * @since  1.0
 */
class AdminController extends BaseAdminController
{
	/**
	 * The default view for the application
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $defaultView = 'lists';

	/**
	 * Execute the controller
	 *
	 * @return  boolean  True if controller finished execution
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		$this->initializeController();

		$task = $this->getInput()->getString('task', 'display');
		$id   = $this->getInput()->getUint('id');

		switch ($task)
		{
			case 'add' :
				$this->getInput()->set('view', 'article');
				$this->getInput()->set('layout', 'add');

				break;

			case 'edit' :
				if (!$id)
				{
					throw new \InvalidArgumentException('A article ID was not provided');
				}

				$this->getInput()->set('view', 'article');
				$this->getInput()->set('layout', 'edit');

				break;

			case 'save' :
				$this->save();
		}

		return parent::execute();
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

		if ($id = $this->getInput()->getUint('id'))
		{
			$state->set('article.id', $id);
		}

		return $state;
	}

	/**
	 * Method to save the category data
	 *
	 * @return  void  Redirects the application
	 *
	 * @since   1.0
	 */
	private function save()
	{
		// Get the input data from the request
		$data = $this->getInput()->post->get('article', array(), 'array');

		// If there aren't any elements, there's nothing to save
		if (!count($data))
		{
			$this->getApplication()
				->enqueueMessage('There was no data to save.')
				->redirect($this->getApplication()->get('uri.base.full') . 'manager/articles');
		}

		// Now we need to filter our data
		$filter       = new InputFilter;
		$filteredData = array();

		$filteredData['isNew'] = !isset($data['id']);

		if (isset($data['title']))
		{
			$filteredData['title'] = $filter->clean($data['title'], 'string');
		}

		if (isset($data['alias']))
		{
			$filteredData['alias'] = $filter->clean($data['alias'], 'string');
		}

		if (isset($data['category']))
		{
			$filteredData['category'] = $filter->clean($data['category'], 'uint');
		}

		if (isset($data['text']))
		{
			$filteredData['text'] = $filter->clean($data['text'], 'raw');
		}

		$filteredData['published'] = isset($data['published']);
		$filteredData['params']    = array();

		foreach ($data['params'] as $key => $value)
		{
			$filteredData['params'][$key] = $filter->clean($data['params'][$key], 'string');
		}

		// We must have an article title
		if ($filteredData['title'] == '')
		{
			$this->getApplication()
				->enqueueMessage('An article title is required.')
				->redirect($this->getApplication()->get('uri.base.full') . 'manager/articles/edit/' . $this->getInput()->getUint('id'));
		}

		/** @var \Extensions\Articles\Model\ArticleModel $model */
		$model = $this->getContainer()->buildObject('\\Extensions\\Articles\\Model\\ArticleModel');
		$model->setState($this->initializeModelState());

		$user = $this->getApplication()->getUser();

		$filteredData['user'] = $user->getId();

		// For proper saving, we need to clear this user object from the EntityManager
		/** @var \Doctrine\ORM\EntityManager $entityManager */
		$entityManager = Factory::get('em');
		$entityManager->clear($user);

		try
		{
			$model->save($this->getInput()->getUint('id', 0), $filteredData);

			$message  = 'Article saved successfully!';
			$redirect = $this->getApplication()->get('uri.base.full') . 'manager/articles';
		}
		catch (\Exception $e)
		{
			$message  = 'An error occurred while saving the article: ' . $e->getMessage();
			$redirect = $this->getApplication()->get('uri.base.full') . 'manager/articles/edit/' . $this->getInput()->getUint('id');
		}

		$this->getApplication()->enqueueMessage($message)->redirect($redirect);
	}
}
