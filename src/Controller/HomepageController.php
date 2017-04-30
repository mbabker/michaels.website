<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Controller rendering the site homepage.
 *
 * @method        Application getApplication() Get the application object.
 * @property-read Application $app             Application object
 */
class HomepageController extends AbstractController
{
    /**
     * @var BlogPostModel
     */
    private $blogModel;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @param RendererInterface $renderer
     * @param BlogPostModel     $blogModel
     */
    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel)
    {
        $this->blogModel = $blogModel;
        $this->renderer  = $renderer;
    }

    public function execute(): bool
    {
        $this->getApplication()->setResponse(
            new HtmlResponse(
                $this->renderer->render('homepage.html.twig', ['latest' => $this->blogModel->getLatestPost()])
            )
        );

        return true;
    }
}
