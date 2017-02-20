<?php

namespace BabDev\Website\Model;

use Joomla\Filesystem\Folder;
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
