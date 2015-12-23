<?php

namespace Bolt\Extension\Gawain\Clippy;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\File\Stylesheet;
use Bolt\Asset\Snippet\Snippet;
use Bolt\Controller\Zone;
use Bolt\Extension\SimpleExtension;
use Bolt\Asset\Target;

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
        return [
            (new Stylesheet('clippy.js/clippy.css'))->setLate(false)->setZone(Zone::BACKEND),
            (new JavaScript('clippy.js/clippy.min.js'))->setLate(true)->setZone(Zone::BACKEND),
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
        return $this->renderTemplate('clippy.twig', array(
            'agent'    => $config['agent'],
            'messages' => $app['session']->getFlashBag()->peek('error')
        ));
    }

    /**
     * Default config options
     *
     * @return array
     */
    protected function getDefaultConfig()
    {
        return array(
            'agent' => 'Clippy'
        );
    }
}
