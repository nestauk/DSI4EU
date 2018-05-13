<?php

namespace DSI\UseCase\ContentUpdates;

use DSI\Entity\ContentUpdate;
use DSI\Repository\ContentUpdateRepo;
use DSI\Service\ErrorHandler;

class RemoveContentUpdate
{
    /** @var ErrorHandler */
    private $errorHandler;

    /** @var ContentUpdateRepo */
    private $contentUpdateRepo;

    /** @var ContentUpdate */
    private $contentUpdate;

    public function __construct(ContentUpdate $contentUpdate)
    {
        $this->errorHandler = new ErrorHandler();
        $this->contentUpdateRepo = new ContentUpdateRepo();
        $this->contentUpdate = $contentUpdate;
    }

    public function exec()
    {
        $this->contentUpdateRepo->delete($this->contentUpdate);
    }
}