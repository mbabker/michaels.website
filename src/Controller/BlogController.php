<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\WebApplication;
use Joomla\Renderer\RendererInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use Pagerfanta\Pagerfanta;

final class BlogController extends AbstractController
{
    private BlogPostModel $blogModel;
    private RendererInterface $renderer;

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel, WebApplication $app)
    {
        parent::__construct($app);

        $this->blogModel = $blogModel;
        $this->renderer  = $renderer;
    }

    public function execute(): bool
    {
        $this->getApplication()->allowCache(true);

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
