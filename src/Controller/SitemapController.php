<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\WebApplication;
use Joomla\Renderer\RendererInterface;
use Laminas\Diactoros\Response\XmlResponse;

final class SitemapController extends AbstractController
{
    private RendererInterface $renderer;
    private BlogPostModel $blogModel;

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel, WebApplication $app)
    {
        parent::__construct($app);

        $this->renderer  = $renderer;
        $this->blogModel = $blogModel;
    }

    public function execute(): bool
    {
        $this->getApplication()->allowCache(true);

        $this->getApplication()->setResponse(
            new XmlResponse(
                $this->renderer->render(
                    'sitemap.xml.twig',
                    [
                        'posts' => $this->blogModel->getPosts(),
                    ]
                )
            )
        );

        return true;
    }
}
