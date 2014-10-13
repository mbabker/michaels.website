<?php
/**
 * Categories extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Categories\Controller;

use BabDev\Website\Controller\AdminController;

use Joomla\Filter\InputFilter;
use Joomla\Registry\Registry;

/**
 * Base controller for the categories extension
 *
 * @since  1.0
 */
class DefaultController extends AdminController
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
			case 'edit' :
				if (!$id)
				{
					throw new \InvalidArgumentException('A category ID was not provided');
				}

				$this->getInput()->set('view', 'category');
				$this->getInput()->set('layout', 'edit');

				break;

			case 'save' :
				if (!$id)
				{
					throw new \InvalidArgumentException('A category ID was not provided');
				}

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
		$state->set('category.extension', $this->getInput()->getString('extension'));

		if ($id = $this->getInput()->getUint('id'))
		{
			$state->set('category.id', $id);
			$state->set('user.current', $this->getApplication()->getUser());
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
		$data      = $this->getInput()->post->get('category', array(), 'array');
		$extension = $this->getInput()->getString('extension');

		// If there aren't any elements, there's nothing to save
		if (!count($data))
		{
			$this->getApplication()
				->enqueueMessage('There was no data to save.')
				->redirect($this->getApplication()->get('uri.base.full') . 'manager/' . $extension . '/categories');
		}

		// Now we need to filter our data
		$filter       = new InputFilter;
		$filteredData = array();

		if (isset($data['title']))
		{
			$filteredData['title'] = $filter->clean($data['title'], 'string');
		}

		if (isset($data['alias']))
		{
			$filteredData['alias'] = $filter->clean($data['alias'], 'string');
		}

		$filteredData['published'] = isset($data['published']);

		// We must have a category title
		if ($filteredData['title'] == '')
		{
			$this->getApplication()
				->enqueueMessage('A category title is required.')
				->redirect($this->getApplication()->get('uri.base.full') . 'manager/' . $extension . '/categories/edit/' . $this->getInput()->getUint('id'));
		}

		/** @var \Extensions\Categories\Model\CategoryModel $model */
		$model = $this->getContainer()->buildObject('\\Extensions\\Categories\\Model\\CategoryModel');
		$model->setState($this->initializeModelState());

		try
		{
			$model->save($this->getInput()->getUint('id'), $filteredData);

			$message  = 'Category saved successfully!';
			$redirect = $this->getApplication()->get('uri.base.full') . 'manager/' . $extension . '/categories';
		}
		catch (\Exception $e)
		{
			$message  = 'An error occurred while saving the category: ' . $e->getMessage();
			$redirect = $this->getApplication()->get('uri.base.full') . 'manager/' . $extension . '/categories/edit/' . $this->getInput()->getUint('id');
		}

		$this->getApplication()->enqueueMessage($message)->redirect($redirect);
	}
}
