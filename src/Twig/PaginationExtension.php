<?php declare(strict_types=1);

namespace BabDev\Website\Twig;

use BabDev\Website\Twig\Service\PaginationService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class PaginationExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_pagination', [PaginationService::class, 'renderPagination'], ['is_safe' => ['html']]),
        ];
    }
}
