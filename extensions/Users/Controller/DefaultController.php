<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Users\Controller;

use BabDev\Website\Controller\AdminController;

use Joomla\Filter\InputFilter;
use Joomla\Registry\Registry;

/**
 * Base controller for the users extension
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
					throw new \InvalidArgumentException('A user ID was not provided');
				}

				$this->getInput()->set('view', 'user');
				$this->getInput()->set('layout', 'edit');

				break;

			case 'save' :
				if (!$id)
				{
					throw new \InvalidArgumentException('A user ID was not provided');
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
		$state->set('user.id', $this->getInput()->getUint('id'));

		return $state;
	}

	/**
	 * Method to save the user data
	 *
	 * @return  void  Redirects the application
	 *
	 * @since   1.0
	 */
	private function save()
	{
		// Get the input data from the request
		$data = $this->getInput()->post->get('user', array(), 'array');

		// If there aren't any elements, there's nothing to save
		if (!count($data))
		{
			$this->getApplication()
				->enqueueMessage('There was no data to save.')
				->redirect($this->getApplication()->get('uri.base.full') . 'manager/users');
		}

		// Now we need to filter our data
		$filter       = new InputFilter;
		$filteredData = array();

		if (isset($data['name']))
		{
			$filteredData['name'] = $filter->clean($data['name'], 'string');
		}

		if (isset($data['username']))
		{
			$filteredData['username'] = $filter->clean($data['username'], 'username');
		}

		if (isset($data['email']))
		{
			$filteredData['email'] = $filter->clean($data['email'], 'string');
		}

		if (isset($data['password']))
		{
			$filteredData['password'] = $filter->clean($data['password'], 'raw');
		}

		if (isset($data['password_verify']))
		{
			$filteredData['password_verify'] = $filter->clean($data['password_verify'], 'raw');
		}

		// Make sure our passwords match
		if (isset($filteredData['password']) && isset($filteredData['password_verify']) && $filteredData['password'] !== $filteredData['password_verify'])
		{
			$this->getApplication()
				->enqueueMessage('There was no data to save.')
				->redirect($this->getApplication()->get('uri.base.full') . 'manager/users/edit/' . $this->getInput()->getUint('id'));
		}

		if (isset($filteredData['password_verify']))
		{
			unset($filteredData['password_verify']);
		}

		/** @var \Extensions\Users\Model\UserModel $model */
		$model = $this->getContainer()->buildObject('\\Extensions\\Users\\Model\\UserModel');
		$model->setState($this->initializeModelState());

		try
		{
			$updatedUser = $model->save($this->getInput()->getUint('id'), $filteredData);

			// Set the updated user entity to the session if this is the logged in user
			if ($this->getApplication()->getUser()->getId() == $this->getInput()->getUint())
			{
				$this->getApplication()->setUser($updatedUser);
			}

			$message  = 'User saved successfully!';
			$redirect = $this->getApplication()->get('uri.base.full') . 'manager/users';
		}
		catch (\Exception $e)
		{
			$message  = 'An error occurred while saving the user: ' . $e->getMessage();
			$redirect = $this->getApplication()->get('uri.base.full') . 'manager/users/edit/' . $this->getInput()->getUint('id');
		}

		$this->getApplication()->enqueueMessage($message)->redirect($redirect);
	}
}
