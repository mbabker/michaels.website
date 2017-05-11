<?php

namespace BabDev\Website\Model;

use BabDev\Website\Entity\BlogPost;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Model for fetching blog posts.
 */
class BlogPostModel
{
    private const BLOG_PATH = JPATH_ROOT . '/pages/blog';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getLatestPost(): BlogPost
    {
        $posts = $this->getPosts();

        return end($posts);
    }

    /**
     * Get the blog posts for a given page as a pagination adapter.
     *
     * @return ArrayAdapter
     */
    public function getPaginatorAdapter(): ArrayAdapter
    {
        $posts = $this->getPosts();

        $orderedPosts = array_reverse($posts);

        return new ArrayAdapter($orderedPosts);
    }

    public function getPost(string $alias): BlogPost
    {
        $finder = Finder::create()
            ->files()
            ->in(self::BLOG_PATH)
            ->name("*_$alias.yml");

        if (count($finder) > 1) {
            throw new \InvalidArgumentException('Non-unique blog post alias given.', 404);
        }

        foreach ($finder as $file) {
            return $this->deserializePost($file->getPathname());
        }

        throw new \InvalidArgumentException('No post found for the given alias.', 404);
    }

    /**
     * @return BlogPost[]
     */
    public function getPosts(): array
    {
        $posts = [];

        foreach ($this->findPostFiles() as $file) {
            $parts            = explode('_', $file->getFilename());
            $posts[$parts[0]] = $this->deserializePost($file->getPathname());
        }

        ksort($posts);

        return $posts;
    }

    private function deserializePost(string $filename): BlogPost
    {
        return $this->serializer->deserialize(
            file_get_contents($filename),
            BlogPost::class,
            'yaml'
        );
    }

    private function findPostFiles(): Finder
    {
        return Finder::create()
            ->files()
            ->in(self::BLOG_PATH)
            ->name('*.yml');
    }
}
