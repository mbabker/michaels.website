<?php
/**
 * Articles extension
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Extensions\Articles\View\Article;

use Extensions\Articles\Model\ArticleModel;
use Joomla\View\BaseHtmlView;

/**
 * HTML view class for interfacing with a single article
 *
 * @since  1.0
 */
class ArticleHtmlView extends BaseHtmlView
{
	/**
	 * The model object.
	 *
	 * @var    ArticleModel
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
	private function add()
	{
		$this->setData([
		    'isNew' => true
		]);

		$layout = explode('.', $this->getLayout());
		$this->setLayout($layout[0] . '.edit');
	}

	/**
	 * Prepares the view when displaying a single article
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function display()
	{
		$this->setData([
			'article' => $this->model->getArticleByAlias(null, null, true)
		]);

		$layout = explode('.', $this->getLayout());
		$this->setLayout($layout[0] . '.index');
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
			'article' => $this->model->getArticle(),
		    'isNew'   => false
		]);
	}
}
