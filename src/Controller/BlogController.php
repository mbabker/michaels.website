<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;
use Pagerfanta\Pagerfanta;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Controller rendering the blog list view.
 *
 * @method        Application getApplication() Get the application object.
 * @property-read Application $app             Application object
 */
final class BlogController extends AbstractController
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
        $page    = $this->getInput()->getUint('page', 1);
        $adapter = $this->blogModel->getPaginatorAdapter();

        $paginator = new Pagerfanta($adapter);
        $paginator->setMaxPerPage($this->getApplication()->get('paginator.per_page', 5));
        $paginator->setCurrentPage($page);

        $this->getApplication()->setResponse(
            new HtmlResponse(
                $this->renderer->render(
                    'blog.html.twig',
                    [
                        'page'      => $page,
                        'paginator' => $paginator,
                        'posts'     => $paginator->getCurrentPageResults(),
                    ]
                )
            )
        );

        return true;
    }
}
