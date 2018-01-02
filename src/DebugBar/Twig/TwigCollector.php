<?php

namespace BabDev\Website\DebugBar\Twig;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\Renderable;

/**
 * Collects data about rendered templates.
 *
 * http://twig.sensiolabs.org/
 *
 * Your Twig_Environment object needs to be wrapped in a
 * TraceableTwigEnvironment object
 *
 * <code>
 * $env = new TraceableTwigEnvironment(new Twig_Environment($loader));
 * $debugbar->addCollector(new TwigCollector($env));
 * </code>
 */
class TwigCollector extends DataCollector implements Renderable, AssetProvider
{
    /**
     * @var TraceableTwigEnvironment
     */
    protected $twig;

    public function __construct(TraceableTwigEnvironment $twig)
    {
        $this->twig = $twig;
    }

    public function collect(): array
    {
        $templates      = [];
        $accuRenderTime = 0;

        foreach ($this->twig->getRenderedTemplates() as $tpl) {
            $accuRenderTime += $tpl['render_time'];
            $templates[]    = [
                'name'            => $tpl['name'],
                'render_time'     => $tpl['render_time'],
                'render_time_str' => $this->formatDuration($tpl['render_time']),
            ];
        }

        return [
            'nb_templates'                => count($templates),
            'templates'                   => $templates,
            'accumulated_render_time'     => $accuRenderTime,
            'accumulated_render_time_str' => $this->formatDuration($accuRenderTime),
        ];
    }

    public function getName(): string
    {
        return 'twig';
    }

    public function getWidgets(): array
    {
        return [
            'twig'       => [
                'icon'    => 'leaf',
                'widget'  => 'PhpDebugBar.Widgets.TemplatesWidget',
                'map'     => 'twig',
                'default' => json_encode(['templates' => []]),
            ],
            'twig:badge' => [
                'map'     => 'twig.nb_templates',
                'default' => 0,
            ],
        ];
    }

    public function getAssets(): array
    {
        return [
            'css' => 'widgets/templates/widget.css',
            'js'  => 'widgets/templates/widget.js',
        ];
    }
}
