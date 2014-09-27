<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Controller;

use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Input\Input;
use Joomla\Registry\Registry;

/**
 * Default controller class for the application
 *
 * @since  1.0
 */
class DefaultController extends AbstractController implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * The application object.
	 *
	 * @var    \BabDev\Website\Application
	 * @since  1.0
	 * @note   Redeclared from parent object for proper typehinting
	 */
	private $app;

	/**
	 * The default view for the application
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $defaultView = 'home';

	/**
	 * Instantiate the controller.
	 *
	 * @param   Input                $input  The input object.
	 * @param   AbstractApplication  $app    The application object.
	 *
	 * @since   1.0
	 */
	public function __construct(Input $input = null, AbstractApplication $app = null)
	{
		parent::__construct($input, $app);

		$this->initializeController();
	}

	/**
	 * Execute the controller
	 *
	 * This is a generic method to execute and render a view and is not suitable for tasks
	 *
	 * @return  boolean  True if controller finished execution
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		try
		{
			// Initialize the view object
			$view = $this->initializeView();

			// Render our view.
			$this->getApplication()->setBody($view->render());

			return true;
		}
		catch (\Exception $e)
		{
			throw new \RuntimeException(sprintf('Error: ' . $e->getMessage()), $e->getCode());
		}
	}

	/**
	 * Get the application object.
	 *
	 * @return  \BabDev\Website\Application  The application object.
	 *
	 * @since   1.0
	 * @throws  \UnexpectedValueException if the application has not been set.
	 * @note    Redeclared from parent object for proper typehinting
	 */
	public function getApplication()
	{
		if ($this->app)
		{
			return $this->app;
		}

		throw new \UnexpectedValueException('Application not set in ' . __CLASS__);
	}

	/**
	 * Method to initialize the controller object, called after the parent constructor has been processed
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initializeController()
	{
		$defaultView = strtolower(str_replace([__NAMESPACE__ . '\\', 'Controller'], '', get_called_class()));
		$this->defaultView = ($defaultView == 'default') ? 'home' : $defaultView;
	}

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
		$model = '\\BabDev\\Website\\Model\\' . ucfirst($this->getInput()->getWord('view', $this->defaultView)) . 'Model';

		// If a model doesn't exist for our view, revert to the default model
		if (!class_exists($model))
		{
			$model = '\\BabDev\\Website\\Model\\DefaultModel';

			// If there still isn't a class, panic.
			if (!class_exists($model))
			{
				throw new \RuntimeException(sprintf('No model found for view %s', $vName), 500);
			}
		}

		$object = $this->getContainer()->buildObject($model);
		$object->setState($this->initializeModelState());

		$this->getContainer()->set($model, $object)->alias('Joomla\\Model\\ModelInterface', $model);
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
		return new Registry;
	}

	/**
	 * Method to initialize the renderer object
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	public function initializeRenderer()
	{
		// Add the provider to the DI container if it doesn't exist
		if (!$this->getContainer()->exists('renderer'))
		{
			$type = $this->getContainer()->get('config')->get('template.renderer');

			// Set the class name for the renderer's service provider
			$class = '\\BabDev\\Website\\Service\\' . ucfirst($type) . 'RendererProvider';

			// Sanity check
			if (!class_exists($class))
			{
				throw new \RuntimeException(sprintf('Renderer provider for renderer type %s not found.', ucfirst($type)));
			}

			$this->getContainer()->registerServiceProvider(new $class($this->getApplication()));
		}
	}

	/**
	 * Method to initialize the view object
	 *
	 * @return  \Joomla\View\ViewInterface  View object
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function initializeView()
	{
		// Initialize the model object
		$this->initializeModel();

		$view   = ucfirst($this->getInput()->getWord('view', $this->defaultView));
		$format = ucfirst($this->getInput()->getWord('format', 'html'));

		$class = '\\BabDev\\Website\\View\\' . $view . '\\' . $view . $format . 'View';

		// Ensure the class exists, fall back to default otherwise
		if (!class_exists($class))
		{
			$class = '\\BabDev\\Website\\View\\Default' . $format . 'View';

			// If we still have nothing, abort mission
			if (!class_exists($class))
			{
				throw new \RuntimeException(sprintf('A view class was not found for the %s view in the %s format.', $view, $format));
			}
		}

		// The view classes have different dependencies, switch it from here
		switch ($format)
		{
			case 'Json' :
				// We can just instantiate the view here
				$object = $this->getContainer()->buildObject($class);

				break;

			case 'Html' :
			default     :
				// HTML views require a renderer object too, fetch it
				$this->initializeRenderer();

				// Instantiate the view now
				$object = $this->getContainer()->buildObject($class);

				// We need to set the layout too
				$object->setLayout(strtolower($view) . '.' . strtolower($this->getInput()->getWord('layout', 'index')));

				// Add the current view path to the loader's lookup if it exists
				if (is_dir(JPATH_TEMPLATES . '/' . strtolower($view)))
				{
					$object->getRenderer()->getLoader()->addPath(JPATH_TEMPLATES . '/' . strtolower($view));
				}

				break;
		}

		return $object;
	}

	/**
	 * Set the application object.
	 *
	 * @param   AbstractApplication  $app  The application object.
	 *
	 * @return  $this
	 *
	 * @since   1.0
	 * @note    Redeclared from parent object for proper typehinting
	 */
	public function setApplication(AbstractApplication $app)
	{
		$this->app = $app;

		return $this;
	}
}
