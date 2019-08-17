<?php

declare(strict_types=1);

namespace App\Http\Software\AnselCraft;

use App\Common\Changelog\ParseChangelogFromJson;
use App\Common\Http\StandardChangelogResponder;
use corbomite\http\exceptions\Http404Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function max;

class GetAnselCraftChangelogAction
{
    /** @var ParseChangelogFromJson */
    private $parser;
    /** @var StandardChangelogResponder */
    private $responder;

    public function __construct(
        ParseChangelogFromJson $parser,
        StandardChangelogResponder $responder
    ) {
        $this->parser    = $parser;
        $this->responder = $responder;
    }

    /**
     * @throws Http404Exception
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $page = (string) $request->getAttribute('page');

        if ($page === '0' || $page === '1') {
            throw new Http404Exception();
        }

        $page = max(1, (int) $page);

        $this->parser->parse(
            'https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md',
            $this->responder
        );

        return $this->responder->createResponseBasedOnInput(
            10,
            $page,
            '/software/ansel-craft/changelog',
            'Ansel for Craft Changelog',
            'anselCraft',
            'anselCraft'
        );
    }
}
