<?php declare(strict_types=1);

namespace BabDev\Website\Controller;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\WebApplication;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class HomepageController extends AbstractController
{
    private BlogPostModel $blogModel;

    private RendererInterface $renderer;

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel, WebApplication $app, Input $input = null)
    {
        parent::__construct($app, $input);

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
