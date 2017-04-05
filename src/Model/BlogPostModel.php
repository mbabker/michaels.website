<?php

namespace BabDev\Website\Model;

use BabDev\Website\Entity\BlogPost;
use Joomla\Filesystem\Folder;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Model for fetching blog posts.
 */
class BlogPostModel
{
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

    /**
     * Get the latest blog post.
     *
     * @return BlogPost
     */
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

    /**
     * Get a single post.
     *
     * @param string $alias The blog post's slug to lookup
     *
     * @return BlogPost
     *
     * @throws \InvalidArgumentException
     */
    public function getPost(string $alias): BlogPost
    {
        $lookupPath = JPATH_ROOT . '/pages/blog';

        $files = Folder::files($lookupPath, '.yml');

        foreach ($files as $file) {
            $parts = explode('_', $file);

            if ($parts[1] === $alias . '.yml') {
                return $this->deserializePost($lookupPath . '/' . $file);
            }
        }

        throw new \InvalidArgumentException('No post found for the given alias.', 404);
    }

    /**
     * Get all blog posts.
     *
     * @return BlogPost[]
     */
    public function getPosts(): array
    {
        $lookupPath = JPATH_ROOT . '/pages/blog';

        $files = Folder::files($lookupPath, '.yml');
        $posts = [];

        foreach ($files as $file) {
            $parts            = explode('_', $file);
            $posts[$parts[0]] = $this->deserializePost($lookupPath . '/' . $file);
        }

        ksort($posts);

        return $posts;
    }

    /**
     * Deserialize the contents of a post's YAML file into a BlogPost entity.
     *
     * @param string $filename
     *
     * @return BlogPost
     */
    private function deserializePost(string $filename): BlogPost
    {
        return $this->serializer->deserialize(
            file_get_contents($filename),
            BlogPost::class,
            'yaml'
        );
    }
}
