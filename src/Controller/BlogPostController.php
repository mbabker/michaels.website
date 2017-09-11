<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Controller rendering single blog posts.
 *
 * @method        Application getApplication() Get the application object.
 * @property-read Application $app             Application object
 */
final class BlogPostController extends AbstractController
{
    /**
     * @var BlogPostModel
     */
    private $blogModel;

    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel)
    {
        $this->blogModel = $blogModel;
        $this->renderer  = $renderer;
    }

    public function execute(): bool
    {
        $alias = $this->getInput()->getPath('alias', '');

        $this->getApplication()->setResponse(
            new HtmlResponse(
                $this->renderer->render(
                    'blog/layout.html.twig',
                    [
                        'post' => $this->blogModel->getPost($alias),
                    ]
                )
            )
        );

        return true;
    }
}
