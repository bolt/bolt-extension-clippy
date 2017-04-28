<?php

namespace Bolt\Extension\Gawain\Clippy;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\File\Stylesheet;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Asset\Target;
use Bolt\Controller\Zone;
use Bolt\Extension\SimpleExtension;

/**
 * Clippy extension for Bolt… 'cause you know you miss the little guy!
 *
 * @author Gawain Lynch <gawain.lynch@gmail.com>
 */
class ClippyExtension extends SimpleExtension
{
    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        $webDir = $this->getWebDirectory()->getPath();

        return [
            (new Stylesheet($webDir . '/clippy.js/clippy.css'))->setLate(false)->setZone(Zone::BACKEND),
            (new JavaScript($webDir . '/clippy.js/clippy.min.js'))->setLate(true)->setZone(Zone::BACKEND),
            (new Snippet())->setCallback([$this, 'clippy'])->setLocation(Target::END_OF_HTML)->setZone(Zone::BACKEND),
        ];
    }

    /**
     * Render the JavaScript that loads Clippy.
     *
     * @return string
     */
    public function clippy()
    {
        $app = $this->getContainer();
        $config = $this->getConfig();

        // Render the JS
        return $this->renderTemplate('clippy.twig', [
            'agent'    => $config['agent'],
            'timer'    => $config['timer'],
            'messages' => $app['session']->getFlashBag()->peek('error'),
        ]);
    }

    /**
     * Default config options
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return [
            'agent' => 'Clippy',
            'timer' => 30,
        ];
    }
}
