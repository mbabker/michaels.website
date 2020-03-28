<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\WebApplication;
use Joomla\Renderer\RendererInterface;
use Laminas\Diactoros\Response\HtmlResponse;

final class HomepageController extends AbstractController
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

        $this->getApplication()->setResponse(
            new HtmlResponse(
                $this->renderer->render('homepage.html.twig', ['latest' => $this->blogModel->getLatestPost()])
            )
        );

        return true;
    }
}
