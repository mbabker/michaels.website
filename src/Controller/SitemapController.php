<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\WebApplication;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Laminas\Diactoros\Response\XmlResponse;

final class SitemapController extends AbstractController
{
    private RendererInterface $renderer;

    private BlogPostModel $blogModel;

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel, WebApplication $app, Input $input = null)
    {
        parent::__construct($app, $input);

        $this->renderer  = $renderer;
        $this->blogModel = $blogModel;
    }

    public function execute(): bool
    {
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
