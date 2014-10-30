<?php

namespace Bolt\Extension\Gawain\Clippy;

use Bolt\Application;
use Bolt\Extensions\Snippets\Location as SnippetLocation;

class Extension extends \Bolt\BaseExtension
{

    public function getName()
    {
        return "Clippy";
    }

    public function __construct(Application $app)
    {
        parent::__construct($app);
        if ($this->app['config']->getWhichEnd()=='backend') {
            $this->app['htmlsnippets'] = true;
        }
    }

    public function initialize()
    {
        $this->addCss('lib/clippy.js/build/clippy.css');
        $this->addJavascript('lib/clippy.js/build/clippy.min.js', true);

        // Add path
        $this->app['twig.loader.filesystem']->addPath(__DIR__ . "/assets");

        // Render the JS
        $html = $this->app['render']->render('clippy.twig', array(
            'agent' => $this->config['agent']
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