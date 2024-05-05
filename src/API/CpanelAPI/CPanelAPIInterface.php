<?php

namespace KemoyCampbell\CpanelAPI\API\CpanelAPI;


use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Interface CPanelAPIInterface
 *
 * Represents an interface for making requests to the cPanel server.
 *
 * @package KemoyCampbell\CpanelAPI\API\CpanelAPI
 */
interface CPanelAPIInterface
{
    /**
     * Makes a request to the cPanel server.
     *
     * @param string $module     The module that the request is being made to.
     *                          A module refers to a specific area or functionality within the cPanel control panel.
     * @param string $function   The function that is being called.
     *                          A function within a cPanel module represents a specific action
     *                          or operation that can be performed within that module.
     * @param array  $parameters The parameters that are being passed to the function.
     *                          Parameters are additional data or information that may be
     *                          required when calling a function within a cPanel module.
     *                          These parameters provide the necessary context or details
     *                              needed to perform the requested action.
     *
     * @return CpanelResponseInterface
     */
    public function request(string $module, string $function, array $parameters = []):CpanelResponseInterface;

}

