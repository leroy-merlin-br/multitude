<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Leadgen\EventType\EventType;
use Leadgen\EventType\Repository;

/**
 * RESTful controller of EventType entity.
 */
class EventTypeController extends ApiController
{
    /**
     * EventType repository
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
     * @param Repository      $repo            EventType repository.
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
        $eventType = $this->repo->createNew($request->all());

        if (! $eventType) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->setStatusCode(201)
            ->respond($eventType);
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
        $eventType = $this->repo->findExisting($id);

        return $this->responseBuilder
            ->respond($eventType);
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
        $eventType = $this->repo->findExisting($id);

        if (! $this->repo->updateExisting($eventType, $request->all())) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond($eventType);
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
        $eventType = $this->repo->findExisting($id);

        if (! $this->repo->deleteExisting($eventType)) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond();
    }
}
