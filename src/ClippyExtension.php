<?php

namespace Bolt\Extension\Gawain\Clippy;

use Bolt\Application;
use Bolt\BaseExtension;
use Bolt\Extensions\Snippets\Location as SnippetLocation;

class ClippyExtension extends BaseExtension
{
    public function getName()
    {
        return 'Clippy';
    }

    public function __construct(Application $app)
    {
        parent::__construct($app);
        if ($this->app['config']->getWhichEnd() === 'backend') {
            $this->app['htmlsnippets'] = true;
        }
    }

    public function initialize()
    {
        if ($this->app['config']->getWhichEnd() === 'backend') {
            $this->app->after(array($this, 'after'));
        }
    }

    public function after()
    {
        $this->addCss('lib/clippy.js/clippy.css');
        $this->addJavascript('lib/clippy.js/clippy.min.js', array('late' => true));

        // Add path
        $this->app['twig.loader.filesystem']->addPath(__DIR__ . '/assets');

        // Render the JS
        $html = $this->app['render']->render('clippy.twig', array(
            'agent'    => $this->config['agent'],
            'messages' => $this->app['session']->getFlashBag()->peek('error')
        ));

        // Add the snippets
        $this->addSnippet(SnippetLocation::END_OF_HTML, $html);
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