<?php

declare(strict_types=1);

namespace App\Http\Software\AnselCraft;

use App\Common\Http\StandardPageResponder;
use BuzzingPixel\Scribble\ScribbleApiContract;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class GetAnselCraftAction
{
    /** @var ScribbleApiContract */
    private $scribbleApi;
    /** @var StandardPageResponder */
    private $standardPageResponder;

    public function __construct(
        ScribbleApiContract $scribbleApi,
        StandardPageResponder $standardPageResponder
    ) {
        $this->scribbleApi           = $scribbleApi;
        $this->standardPageResponder = $standardPageResponder;
    }

    /**
     * @throws Throwable
     */
    public function __invoke() : ResponseInterface
    {
        $this->scribbleApi->getContentFromPath(
            'Software/AnselCraft',
            $this->standardPageResponder
        );

        return $this->standardPageResponder->createResponseBasedOnInput();
    }
}
