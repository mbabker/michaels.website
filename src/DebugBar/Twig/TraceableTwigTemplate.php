<?php

namespace BabDev\Website\DebugBar\Twig;

class TraceableTwigTemplate
{
    /**
     * @var TraceableTwigEnvironment
     */
    protected $env;

    /**
     * @var \Twig_Template
     */
    protected $template;

    public function __construct(TraceableTwigEnvironment $env, \Twig_Template $template)
    {
        $this->env      = $env;
        $this->template = $template;
    }

    public function __toString()
    {
        return $this->getTemplateName();
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->template, $name], $arguments);
    }

    public function getTemplateName(): string
    {
        return $this->template->getTemplateName();
    }

    public function getDebugInfo(): array
    {
        return $this->template->getDebugInfo();
    }

    public function getSourceContext(): \Twig_Source
    {
        return $this->template->getSourceContext();
    }

    public function getParent(array $context)
    {
        return $this->template->getParent($context);
    }

    public function isTraitable(): bool
    {
        return $this->template->isTraitable();
    }

    public function displayParentBlock($name, array $context, array $blocks = []): void
    {
        $this->template->displayParentBlock($name, $context, $blocks);
    }

    public function displayBlock($name, array $context, array $blocks = [], $useBlocks = true): void
    {
        $this->template->displayBlock($name, $context, $blocks, $useBlocks);
    }

    public function renderParentBlock($name, array $context, array $blocks = []): string
    {
        return $this->template->renderParentBlock($name, $context, $blocks);
    }

    public function renderBlock($name, array $context, array $blocks = [], $useBlocks = true): string
    {
        return $this->template->renderBlock($name, $context, $blocks, $useBlocks);
    }

    public function hasBlock($name): bool
    {
        return $this->template->hasBlock($name);
    }

    public function getBlockNames(): array
    {
        return $this->template->getBlockNames();
    }

    public function getBlocks(): array
    {
        return $this->template->getBlocks();
    }

    public function display(array $context, array $blocks = []): void
    {
        $start = microtime(true);
        $this->template->display($context, $blocks);
        $end = microtime(true);

        if ($timeDataCollector = $this->env->getTimeDataCollector()) {
            $name = sprintf('twig.render(%s)', $this->template->getTemplateName());
            $timeDataCollector->addMeasure($name, $start, $end);
        }

        $this->env->addRenderedTemplate(
            [
                'name'        => $this->template->getTemplateName(),
                'render_time' => $end - $start,
            ]
        );
    }

    public function render(array $context): string
    {
        $level = ob_get_level();
        ob_start();

        try {
            $this->display($context);
        } catch (Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        }

        return ob_get_clean();
    }
}
