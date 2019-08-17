<?php

declare(strict_types=1);

namespace Tests\Http\Software\AnselCraft;

use App\Common\Changelog\ParseChangelogVersionFromJson;
use App\Common\Http\StandardChangelogVersionResponder;
use App\Http\Software\AnselCraft\GetAnselCraftChangelogVersionAction;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class GetAnselCraftChangelogVersionActionTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function test() : void
    {
        /** @var ParseChangelogVersionFromJson&MockObject $parser */
        $parser = $this->createMock(ParseChangelogVersionFromJson::class);

        /** @var StandardChangelogVersionResponder&MockObject $responder */
        $responder = $this->createMock(StandardChangelogVersionResponder::class);

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);

        /** @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);

        $parser->expects(self::once())
            ->method('parse')
            ->with(
                self::equalTo('https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md'),
                self::equalTo('versionVal'),
                self::equalTo($responder)
            );

        $request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('version'))
            ->willReturn('versionVal');

        $responder->expects(self::once())
            ->method('createResponseBasedOnInput')
            ->with(
                self::equalTo('/software/ansel-craft/changelog'),
                self::equalTo('Ansel for Craft Changelog'),
                self::equalTo('anselCraft'),
                self::equalTo('anselCraft'),
                self::equalTo('Ansel for Craft Changelog')
            )
            ->willReturn($response);

        self::assertSame(
            $response,
            (new GetAnselCraftChangelogVersionAction($parser, $responder))($request)
        );
    }
}
