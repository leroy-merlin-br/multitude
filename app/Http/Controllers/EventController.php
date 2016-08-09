<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Leadgen\Interaction\Repository;

/**
 * Controller to handle the creation of Interactions
 */
class InteractionController extends ApiController
{
    /**
     * Interaction repository
     * @var Repository
     */
    protected $repo;

    /**
     * To build the server response
     * @var ResponseBuilder;
     */
    protected $responseBuilder;

    /**
     * Constructor
     *
     * @param Repository      $repo            Interaction repository.
     * @param ResponseBuilder $responseBuilder To build the server response.
     */
    public function __construct(Repository $repo, ResponseBuilder $responseBuilder)
    {
        $this->repo            = $repo;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * Stores a new resource
     *
     * @param  Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $interaction = $this->repo->createNew($request->all());

        if (! $interaction) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->setStatusCode(201)
            ->respond($interaction);
    }
}
