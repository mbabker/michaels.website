<?php
/**
 * Application powering http://michaels.website
 *
 * @copyright  Copyright (C) 2014 Michael Babker. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace BabDev\Website\Controller;

use BabDev\Website\Application;

use Joomla\Application\AbstractApplication;
use Joomla\Controller\AbstractController;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use Joomla\View\BaseHtmlView;

/**
 * Default controller class for the application
 *
 * @method         Application  getApplication()  Get the application object.
 * @property-read  Application  $app              Application object
 *
 * @since          1.0
 */
class DefaultController extends AbstractController implements ContainerAwareInterface
{
	use ContainerAwareTrait;

	/**
	 * The default view for the application
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $defaultView = 'home';

	/**
	 * The extension being executed
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $extension;

	/**
	 * Flag if the application is in the administrator section
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $isAdmin;

	/**
	 * Flag if the controller has been initialized
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $initialized = false;

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

		// Detect the extension name
		if (empty($this->extension))
		{
			// Get the fully qualified class name for the current object
			$fqcn = (get_class($this));

			// Strip the base namespace off
			$className = str_replace('Extensions\\', '', $fqcn);

			// Explode the remaining name into an array
			$classArray = explode('\\', $className);

			// Set the extension as the first object in this array
			$this->extension = $classArray[0];
		}

		$this->isAdmin = ($this instanceof AdminController);
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
		$this->initializeController();

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
	 * Method to initialize the controller object, called after the parent constructor has been processed
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function initializeController()
	{
		if (!$this->initialized)
		{
			$replacements = [__NAMESPACE__ . '\\', 'Extensions\\' . $this->extension . '\\Controller\\', 'Controller'];
			$defaultView = strtolower(str_replace($replacements, '', get_called_class()));
			$this->defaultView = (in_array($defaultView, ['default', 'admin'])) ? $this->defaultView : $defaultView;
			$this->initialized = true;
		}
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
		$model = '\\Extensions\\' . $this->extension . '\\Model\\' . ucfirst($this->getInput()->getWord('view', $this->defaultView)) . 'Model';

		// If a model doesn't exist for the view, check for a default model in the extension
		if (!class_exists($model))
		{
			$model = '\\Extensions\\' . $this->extension . '\\Model\\DefaultModel';

			// If an extension default doesn't exist, revert to the application default
			if (!class_exists($model))
			{
				$model = '\\BabDev\\Website\\Model\\DefaultModel';

				// If there still isn't a class, panic.
				if (!class_exists($model))
				{
					throw new \RuntimeException(sprintf('No model found for view %s', $vName), 500);
				}
			}
		}

		/** @var \Joomla\Model\ModelInterface $object */
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
		return $model->getState();
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

		$class = '\\Extensions\\' . $this->extension . '\\View\\' . $view . '\\' . $view . $format . 'View';

		// Ensure the class exists, fall back to the extension's default view otherwise
		if (!class_exists($class))
		{
			$class = '\\Extensions\\' . $this->extension . '\\View\\Default' . $format . 'View';

			// If an extension default view doesn't exist, fall back to the default application view
			if (!class_exists($class))
			{
				$class = '\\BabDev\\Website\\View\\Default' . $format . 'View';

				// If we still have nothing, maybe Joomla core has an option
				if (!class_exists($class))
				{
					$class = '\\Joomla\\View\\Base' . $format . 'View';

					// Still nothing?  Well, with this many options, we can say we did our best.
					if (!class_exists($class))
					{
						throw new \RuntimeException(
							sprintf('A view class was not found for the %s view in the %s format.', $view, $format)
						);
					}
				}
			}
		}

		$object = $this->getContainer()->buildObject($class);

		// Add paths to the HTML view
		if ($object instanceof BaseHtmlView)
		{
			// We need to set the layout too
			$object->setLayout(strtolower($view) . '.' . strtolower($this->getInput()->getWord('layout', 'index')));

			// Add the extension's view path if it exists
			if (is_dir(JPATH_TEMPLATES . '/' . strtolower($this->extension)))
			{
				$object->getRenderer()->getRenderer()->getLoader()->prependPath(JPATH_TEMPLATES . '/' . strtolower($this->extension));
			}
		}

		return $object;
	}
}
