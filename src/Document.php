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
     * @var Client
     */
    private $client;

    /**
     * @var Database
     */
    private $database;

    /**
     * @param \stdClass $document
     * @param Client $client
     * @param Database $database
     */
    public function __construct(\stdClass $document, Client $client, Database $database)
    {
        $this->document = $document;
        $this->id = $document->_id;
        $this->rev = $document->_rev;
        $this->client = $client;
        $this->database = $database;
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

    /**
     * Attach a file to this document.
     *
     * @param string $fileName the absolute path to a file on the file system
     *
     * @return mixed
     */
    public function saveAttachment($fileName)
    {
        if (!file_exists($fileName)) {
            $message = sprintf('The attachment "%s" does not exist', $fileName);
            throw new \InvalidArgumentException($message);
        }

        $cf = curl_file_create($fileName, mime_content_type($fileName), basename($fileName));
        $url = $this->getDocumentUrl() . '/' . basename($fileName);
        $params = ['rev' => $this->getRevision()];

        $curl = $this->client->getCurlClient();
        return $curl->put($url, $params, $cf)->getJsonBody();
    }

    /**
     * @return string
     */
    public function getDocumentUrl()
    {
        return sprintf('%s/%s', $this->database->getDatabaseUrl(), $this->getId());
    }
}
