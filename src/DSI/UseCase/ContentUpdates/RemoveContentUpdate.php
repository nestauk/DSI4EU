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

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
        $this->contentUpdateRepo = new ContentUpdateRepo();
    }

    public function exec()
    {
        $this->contentUpdateRepo->delete($this->contentUpdate);
    }

    /**
     * @param ContentUpdate $contentUpdate
     * @return RemoveContentUpdate
     */
    public function setContentUpdate(ContentUpdate $contentUpdate)
    {
        $this->contentUpdate = $contentUpdate;
        return $this;
    }
}