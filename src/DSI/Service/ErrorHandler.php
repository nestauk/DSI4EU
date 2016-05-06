<?php

namespace DSI\Service;

class ErrorHandler extends \Exception
{
    private $errors = array();

    public function addError($error)
    {
        if ($error != '')
            $this->errors[] = $error;
    }

    public function addTaggedError($tag, $error)
    {
        if ($error != '')
            $this->errors[ $tag ] = $error;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function clearErrors()
    {
        $this->errors = array();
    }

    public function hasErrors()
    {
        return $this->errors ? true : false;
    }

    public function getTaggedError($tag)
    {
        if(isset($this->errors[$tag]))
            return $this->errors[$tag];

        return null;
    }

    public function throwIfNotEmpty()
    {
        if ($this->hasErrors())
            throw $this;
    }
}

class ErrorHandler_hasErrors extends \Exception
{
}