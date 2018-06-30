<?php

namespace BabDev\Website\Model;

use BabDev\Website\Entity\BlogPost;
use Pagerfanta\Adapter\ArrayAdapter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Serializer\SerializerInterface;

final class BlogPostModel
{
    private const BLOG_PATH = JPATH_ROOT . '/pages/blog';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getLatestPost(): BlogPost
    {
        $posts = $this->getPosts();

        return end($posts);
    }

    public function getPaginatorAdapter(): ArrayAdapter
    {
        $posts = $this->getPosts();

        $orderedPosts = array_reverse($posts);

        return new ArrayAdapter($orderedPosts);
    }

    public function getPost(string $alias): BlogPost
    {
        $finder = $this->buildFinder()->name("*_$alias.md");

        if (\count($finder) > 1) {
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

    private function buildFinder(): Finder
    {
        return Finder::create()
            ->files()
            ->in(self::BLOG_PATH);
    }

    private function deserializePost(string $filename): BlogPost
    {
        $frontMatter = YamlFrontMatter::parseFile($filename);

        return $this->serializer->deserialize(
            json_encode(array_merge($frontMatter->matter(), ['text' => $frontMatter->body()])),
            BlogPost::class,
            'json'
        );
    }

    private function findPostFiles(): Finder
    {
        return $this->buildFinder()->name('*.md');
    }
}
