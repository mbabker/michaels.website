<?php

namespace BabDev\Website\Model;

use Joomla\Filesystem\Folder;
use Pagerfanta\Adapter\ArrayAdapter;
use Symfony\Component\Yaml\Parser;

/**
 * Model for fetching blog posts.
 */
class BlogPostModel
{
    /**
     * Get the latest blog post.
     *
     * @return array
     */
    public function getLatestPost(): array
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
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function getPost(string $alias): array
    {
        $lookupPath = JPATH_ROOT . '/pages/blog';

        $files = Folder::files($lookupPath, '.yml');

        foreach ($files as $file) {
            $parts = explode('_', $file);

            if ($parts[1] === $alias . '.yml') {
                return (new Parser)->parse(file_get_contents($lookupPath . '/' . $file));
            }
        }

        throw new \InvalidArgumentException('No post found for the given alias.', 404);
    }

    /**
     * Get all blog posts.
     *
     * @return array
     */
    public function getPosts(): array
    {
        $lookupPath = JPATH_ROOT . '/pages/blog';

        $files = Folder::files($lookupPath, '.yml');
        $posts = [];

        foreach ($files as $file) {
            $parts            = explode('_', $file);
            $posts[$parts[0]] = (new Parser)->parse(file_get_contents($lookupPath . '/' . $file));
        }

        ksort($posts);

        return $posts;
    }
}
