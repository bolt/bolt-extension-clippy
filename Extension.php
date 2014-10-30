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

        $this->app['twig.loader.filesystem']->addPath(__DIR__ . "/assets");
        $html = $this->app['render']->render('clippy.twig', array());
        $this->addSnippet(SnippetLocation::END_OF_HTML, $html);
    }
}