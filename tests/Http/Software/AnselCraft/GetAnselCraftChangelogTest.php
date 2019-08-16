<?php

declare(strict_types=1);

namespace BuzzingPixel\Tests\Http\Software\AnselCraft;

use App\Common\Changelog\ParseChangelogFromJson;
use App\Common\Http\StandardChangelogResponder;
use App\Http\Software\AnselCraft\GetAnselCraftChangelog;
use corbomite\http\exceptions\Http404Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class GetAnselCraftChangelogTest extends TestCase
{
    /** @var GetAnselCraftChangelog */
    private $object;

    /** @var MockObject|ResponseInterface */
    private $response;
    /** @var ParseChangelogFromJson|MockObject */
    private $parseChangelogFromJson;
    /** @var StandardChangelogResponder|MockObject */
    private $standardChangelogResponder;
    /** @var MockObject|ServerRequestInterface */
    private $request;

    protected function setUp() : void
    {
        /** @var ResponseInterface&MockObject $response */
        $response = $this->createMock(ResponseInterface::class);

        /** @var ParseChangelogFromJson&MockObject $parseChangelogFromJson */
        $parseChangelogFromJson = $this->createMock(ParseChangelogFromJson::class);

        /** @var StandardChangelogResponder&MockObject $standardChangelogResponder */
        $standardChangelogResponder = $this->createMock(StandardChangelogResponder::class);

        /** @var ServerRequestInterface&MockObject $request */
        $request = $this->createMock(ServerRequestInterface::class);

        $this->response = $response;

        $this->parseChangelogFromJson = $parseChangelogFromJson;

        $this->standardChangelogResponder = $standardChangelogResponder;

        $this->request = $request;

        $this->object = new GetAnselCraftChangelog(
            $this->parseChangelogFromJson,
            $this->standardChangelogResponder
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIs0() : void
    {
        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn('0');

        self::expectException(Http404Exception::class);

        ($this->object)($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIs1() : void
    {
        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn('1');

        self::expectException(Http404Exception::class);

        ($this->object)($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIsNull() : void
    {
        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn(null);

        $this->parseChangelogFromJson->expects(self::once())
            ->method('parse')
            ->with(
                self::equalTo(
                    'https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md'
                ),
                self::equalTo($this->standardChangelogResponder)
            );

        $this->standardChangelogResponder->expects(self::once())
            ->method('createResponseBasedOnInput')
            ->with(
                self::equalTo(10),
                self::equalTo(1),
                self::equalTo('/software/ansel-craft/changelog'),
                self::equalTo('Ansel for Craft Changelog'),
                self::equalTo('anselCraft'),
                self::equalTo('anselCraft')
            )
            ->willReturn($this->response);

        ($this->object)($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIs3() : void
    {
        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn('3');

        $this->parseChangelogFromJson->expects(self::once())
            ->method('parse')
            ->with(
                self::equalTo(
                    'https://raw.githubusercontent.com/buzzingpixel/ansel-craft/master/changelog.md'
                ),
                self::equalTo($this->standardChangelogResponder)
            );

        $this->standardChangelogResponder->expects(self::once())
            ->method('createResponseBasedOnInput')
            ->with(
                self::equalTo(10),
                self::equalTo(3),
                self::equalTo('/software/ansel-craft/changelog'),
                self::equalTo('Ansel for Craft Changelog'),
                self::equalTo('anselCraft'),
                self::equalTo('anselCraft')
            )
            ->willReturn($this->response);

        ($this->object)($this->request);
    }
}
