<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Model\BlogPostModel;
use Joomla\Application\WebApplication;
use Joomla\Input\Input;
use Joomla\Renderer\RendererInterface;
use Zend\Diactoros\Response\HtmlResponse;

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

    public function __construct(RendererInterface $renderer, BlogPostModel $blogModel, WebApplication $app, Input $input = null)
    {
        parent::__construct($app, $input);

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
