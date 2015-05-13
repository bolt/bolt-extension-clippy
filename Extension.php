<?php

namespace Bolt\Extension\Gawain\Clippy;

use Bolt\Application;
use Bolt\BaseExtension;
use Bolt\Extensions\Snippets\Location as SnippetLocation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Extension extends BaseExtension
{

    public function getName()
    {
        return "Clippy";
    }

    public function __construct(Application $app)
    {
        parent::__construct($app);
        if ($this->app['config']->getWhichEnd() == 'backend') {
            $this->app['htmlsnippets'] = true;
        }
    }

    public function initialize()
    {
        if ($this->app['config']->getWhichEnd() == 'backend') {
            $this->addCss('lib/clippy.js/clippy.css');
            $this->addJavascript('lib/clippy.js/clippy.min.js', true);

            // Add path
            $this->app['twig.loader.filesystem']->addPath(__DIR__ . "/assets");

            // Render the JS
            $html = $this->app['render']->render('clippy.twig', array(
                'agent' => $this->config['agent'],
                'messages' => $this->app['session']->getFlashBag()->peek('error')
            ));

            // Enable snippets to be loaded in the backend
            $this->app->before(function (Request $request, Application $app) {
                $request->attributes->set('allow_snippets', true);
            });

            // Add the snippets
            $this->addSnippet(SnippetLocation::END_OF_HTML, $html);
        }
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