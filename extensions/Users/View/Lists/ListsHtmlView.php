<?php
/**
 * Users extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Users\View\Lists;

use BabDev\Website\View\AbstractHtmlView;

use Extensions\Users\Model\ListsModel;

/**
 * HTML view class for listing the application's users
 *
 * @since  1.0
 */
class ListsHtmlView extends AbstractHtmlView
{
	/**
	 * The model object.
	 *
	 * @var    ListsModel
	 * @since  1.0
	 */
	protected $model;

	/**
	 * Method to render the view.
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function render()
	{
		$this->setData([
			'users' => $this->model->getUsers()
		]);

		return parent::render();
	}
}
