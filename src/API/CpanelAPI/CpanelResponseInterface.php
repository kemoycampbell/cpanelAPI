<?php

namespace KemoyCampbell\CpanelAPI\API\CpanelAPI;

interface CpanelResponseInterface
{
    public function getResponseAsArray():array;
    public function getResponseAsJson():string;
    public function getResponseAsStdClass():\stdClass;
    public function getResponseAsString():string;
    public function getResponseAsXml():\SimpleXMLElement;

}