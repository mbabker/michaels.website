<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

final class HomepageController extends AbstractController
{
    /**
     * @var BlogPostModel
     */
    private $blogModel;

    /**
     * @var RendererInterface
     */
    private $renderer;

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel, Application $app, Input $input = null)
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
