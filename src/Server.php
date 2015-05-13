<?php

namespace Couchy;

class Server
{
    /**
     * @var \stdClass
     */
    private $rawData;

    public function __construct(\stdClass $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return isset($this->rawData->uuid) ? $this->rawData->uuid : '';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return isset($this->rawData->version) ? $this->rawData->version : '';
    }

    /**
     * @return array
     */
    public function getVendor()
    {
        return [
            'version' => isset($this->rawData->vendor->version) ?
                $this->rawData->vendor->version : null,
            'name' => isset($this->rawData->vendor->name) ?
                $this->rawData->vendor->name : null,
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'version' => $this->getVersion(),
            'uuid' => $this->getUuid(),
            'vendor' => $this->getVendor(),
        ];
    }
}
