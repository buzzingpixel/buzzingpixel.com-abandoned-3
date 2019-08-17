<?php

declare(strict_types=1);

namespace App\Http\Software\AnselCraft;

use App\Common\Changelog\ParseChangelogVersionFromJson;
use App\Common\Http\StandardChangelogVersionResponder;
use corbomite\http\exceptions\Http404Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetAnselCraftChangelogVersionAction
{
    /** @var ParseChangelogVersionFromJson */
    private $parser;
    /** @var StandardChangelogVersionResponder */
    private $responder;

    public function __construct(
        ParseChangelogVersionFromJson $parser,
        StandardChangelogVersionResponder $responder
    ) {
        $this->parser    = $parser;
        $this->responder = $responder;
    }

    /**
     * @throws Http404Exception
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $this->parser->parse(
            'https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md',
            (string) $request->getAttribute('version'),
            $this->responder
        );

        return $this->responder->createResponseBasedOnInput(
            '/software/ansel-craft/changelog',
            'Ansel for Craft Changelog',
            'anselCraft',
            'anselCraft',
            'Ansel for Craft Changelog'
        );
    }
}
