<?php declare(strict_types=1);

namespace BabDev\Website\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('first_paragraph', [$this, 'getFirstParagraph']),
        ];
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('get_class', 'get_class'),
            new TwigFilter('strip_root_path', [$this, 'stripRootPath']),
        ];
    }

    public function getFirstParagraph(string $text): string
    {
        preg_match("/<p>(.*)<\/p>/", $text, $matches);

        return strip_tags(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
    }

    public function stripRootPath(string $string): string
    {
        return str_replace(JPATH_ROOT, '', $string);
    }
}
