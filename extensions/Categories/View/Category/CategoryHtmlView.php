<?php
/**
 * Category extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Categories\View\Category;

use BabDev\Website\View\AbstractHtmlView;

use Extensions\Categories\Model\CategoryModel;

/**
 * HTML view class for interfacing with a single category
 *
 * @since  1.0
 */
class CategoryHtmlView extends AbstractHtmlView
{
	/**
	 * The model object.
	 *
	 * @var    CategoryModel
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
		$layout = explode('.', $this->getLayout());

		if (method_exists($this, $layout[1]))
		{
			$this->{$layout[1]}();
		}

		return parent::render();
	}

	/**
	 * Prepares the view when using the edit layout
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function edit()
	{
		$this->setData([
			'category'  => $this->model->getCategory(),
		    'extension' => $this->model->getState()->get('category.extension')
		]);
	}
}
