<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;
use Pagerfanta\Pagerfanta;

/**
 * Controller rendering the blog list view.
 *
 * @method         Application  getApplication()  Get the application object.
 * @property-read  Application $app              Application object
 */
class BlogController extends AbstractController
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
     * @param RendererInterface $renderer  The template renderer.
     * @param BlogPostModel     $blogModel The blog model.
     */
    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel)
    {
        $this->blogModel = $blogModel;
        $this->renderer  = $renderer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function execute(): bool
    {
        $page    = $this->getInput()->getUint('page', 1);
        $adapter = $this->blogModel->getPaginatorAdapter();

        $paginator = new Pagerfanta($adapter);
        $paginator->setMaxPerPage($this->getApplication()->get('paginator.per_page', 5));
        $paginator->setCurrentPage($page);

        $this->getApplication()->setBody(
            $this->renderer->render(
                'blog.html.twig',
                [
                    'page'      => $page,
                    'paginator' => $paginator,
                    'posts'     => $paginator->getCurrentPageResults(),
                ]
            )
        );

        return true;
    }
}