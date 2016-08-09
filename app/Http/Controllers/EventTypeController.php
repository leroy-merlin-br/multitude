<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Leadgen\InteractionType\InteractionType;
use Leadgen\InteractionType\Repository;

/**
 * RESTful controller of InteractionType entity.
 */
class InteractionTypeController extends ApiController
{
    /**
     * InteractionType repository
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
     * @param Repository      $repo            InteractionType repository.
     * @param ResponseBuilder $responseBuilder To build the server response.
     */
    public function __construct(Repository $repo, ResponseBuilder $responseBuilder)
    {
        $this->repo            = $repo;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);

        return $this->responseBuilder
            ->respond($this->repo->all($page));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $interactionType = $this->repo->createNew($request->all());

        if (! $interactionType) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->setStatusCode(201)
            ->respond($interactionType);
    }

    /**
     * Display the specified resource.
     *
     * @param  mixed $id Id of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $interactionType = $this->repo->findExisting($id);

        return $this->responseBuilder
            ->respond($interactionType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request Client request.
     * @param mixed   $id      Id of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $interactionType = $this->repo->findExisting($id);

        if (! $this->repo->updateExisting($interactionType, $request->all())) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond($interactionType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param mixed $id Id of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interactionType = $this->repo->findExisting($id);

        if (! $this->repo->deleteExisting($interactionType)) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond();
    }
}
