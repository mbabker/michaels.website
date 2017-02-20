<?php

namespace BabDev\Website\Controller;

use BabDev\Website\Application;
use BabDev\Website\Model\BlogPostModel;
use Joomla\Controller\AbstractController;
use Joomla\Renderer\RendererInterface;

/**
 * Controller rendering single blog posts.
 *
 * @method         Application  getApplication()  Get the application object.
 * @property-read  Application  $app              Application object
 */
class BlogPostController extends AbstractController
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
        $alias = $this->getInput()->getPath('alias', '');

        $this->getApplication()->setBody(
            $this->renderer->render(
                'blog/layout.html.twig',
                [
                    'post' => $this->blogModel->getPost($alias),
                ]
            )
        );

        return true;
    }
}
