<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\WebApplication;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Pagerfanta\Pagerfanta;
use Zend\Diactoros\Response\HtmlResponse;

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

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel, WebApplication $app, Input $input = null)
    {
        parent::__construct($app, $input);

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
