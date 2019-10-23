<?php

namespace Satisfy\Traits;

/**
 * Trait RenderTrait
 * @package Satisfy\Traits
 */
trait RenderTrait
{
    /**
     * @var
     */
    protected $renderEngine;

    /**
     * @param       $source
     * @param array $vars
     *
     * @return string
     */
    public function render($source, array $vars = [])
    {
        if ($this->renderEngine === null) {
            $this->renderEngine = new \Twig\Environment(
                new \Twig\Loader\ArrayLoader()
            );
        }

        if (file_exists($source)) {
            $source = file_get_contents($source);
        }

        $name = md5($source);
        $tmpl = $this->renderEngine->createTemplate($source, $name);

        return $this->renderEngine->render($tmpl, $vars);
    }
}