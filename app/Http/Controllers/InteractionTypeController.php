<?php

namespace App\Http\Controllers;

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
     * InteractionType repository.
     *
     * @var Repository
     */
    protected $repo;

    /**
     * To build the server response.
     *
     * @var ResponseBuilder;
     */
    protected $responseBuilder;

    /**
     * Constructor.
     *
     * @param Repository      $repo            InteractionType repository.
     * @param ResponseBuilder $responseBuilder To build the server response.
     */
    public function __construct(Repository $repo, ResponseBuilder $responseBuilder)
    {
        $this->repo = $repo;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * Display a listing of the resource.
     *
     * @SWG\Get(
     *     path="/interactionType",
     *     summary="Retrieve a list of interactionTypes",
     *     tags={"interactionType"},
     *     description="Retrieves a list of interactionTypes with pagination support.",
     *     operationId="interactionType.index",
     *     @SWG\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page to be retrieved",
     *         required=false,
     *         type="integer",
     *         default=1,
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="List of existent interactionTypes",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", type="array", @SWG\Items(ref="#/definitions/InteractionType")),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     )
     * )
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
     * @SWG\Post(
     *     path="/interactionType",
     *     summary="Creates a new interactionType",
     *     tags={"interactionType"},
     *     description="Creates a new interactionType entity.",
     *     operationId="interactionType.store",
     *     @SWG\Parameter(
     *         name="interactionType",
     *         in="body",
     *         description="Segment to be created",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/InteractionType",
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="The newly created interactionType",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/InteractionType"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     )
     * )
     *
     * @param Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $interactionType = $this->repo->createNew($request->all());

        if (!$interactionType) {
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
     * @SWG\Get(
     *     path="/interactionType/{id}",
     *     summary="Retrieve a specific interactionType",
     *     tags={"interactionType"},
     *     description="You can retrieve a interactionType by it's _id or email",
     *     operationId="interactionType.show",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the interactionType to be retrieved",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Segment data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/InteractionType"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Segment not found",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     )
     * )
     *
     * @param mixed $id Id of the resource.
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
     * @SWG\Put(
     *     path="/interactionType/{id}",
     *     summary="Updates a interactionType",
     *     tags={"interactionType"},
     *     description="Updates a interactionType entity.",
     *     operationId="interactionType.update",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the interactionType to be updated",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="interactionType",
     *         in="body",
     *         description="Values that will overwrite the attributes of the interactionType if the id on the url.",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/InteractionType",
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="The updated interactionType in it's new state.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/InteractionType"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Segment not found. A interactionType with the given id was not found to be updated.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request. If was not possible to update the interactionType.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     )
     * )
     *
     * @param Request $request Client request.
     * @param mixed   $id      Id of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $interactionType = $this->repo->findExisting($id);

        if (!$this->repo->updateExisting($interactionType, $request->all())) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond($interactionType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @SWG\Delete(
     *     path="/interactionType/{id}",
     *     summary="Deletes an existing interactionType",
     *     tags={"interactionType"},
     *     description="Deletes a interactionType.",
     *     operationId="interactionType.delete",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the interactionType to be deleted",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Segment not found. A interactionType with the given id was not found to be deleted.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request. If was not possible to delete the interactionType.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     )
     * )
     *
     * @param mixed $id Id of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $interactionType = $this->repo->findExisting($id);

        if (!$this->repo->deleteExisting($interactionType)) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond();
    }
}
