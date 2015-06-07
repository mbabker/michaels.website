<?php
/**
 * Categories extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Categories\View\Lists;

use Extensions\Categories\Model\ListsModel;
use Joomla\View\BaseHtmlView;

/**
 * HTML view class for listing an extension's categories
 *
 * @since  1.0
 */
class ListsHtmlView extends BaseHtmlView
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
			'categories' => $this->model->getCategories(),
		    'extension'  => $this->model->getState()->get('category.extension')
		]);

		return parent::render();
	}
}
