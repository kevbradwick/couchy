<?php

namespace Couchy;

class Document
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $rev;

    /**
     * @var \stdClass
     */
    private $document;

    /**
     * @param \stdClass $document
     */
    public function __construct(\stdClass $document)
    {
        $this->document = $document;
        $this->id = $document->_id;
        $this->rev = $document->_rev;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRevision()
    {
        return $this->rev;
    }

    /**
     * @param string $name
     * @return null
     */
    public function __get($name)
    {
        if (property_exists($this->document, $name)) {
            return $this->document->{$name};
        }

        return null;
    }
}
