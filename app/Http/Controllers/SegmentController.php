<?php

namespace App\Http\Controllers;

use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Leadgen\Segment\Segment;
use Leadgen\Segment\Repository;

/**
 * RESTful controller of Segment entity.
 */
class SegmentController extends ApiController
{
    /**
     * Segment repository.
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
     * @param Repository      $repo            Segment repository.
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
     *     path="/segment",
     *     summary="Retrieve a list of segments",
     *     tags={"segment"},
     *     description="Retrieves a list of segments with pagination support.",
     *     operationId="segment.index",
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
     *         description="List of existent segments",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", type="array", @SWG\Items(ref="#/definitions/Segment")),
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
     *     path="/segment",
     *     summary="Creates a new segment",
     *     tags={"segment"},
     *     description="Creates a new segment entity.",
     *     operationId="segment.store",
     *     @SWG\Parameter(
     *         name="segment",
     *         in="body",
     *         description="Segment to be created",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/Segment",
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="The newly created segment",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/Segment"),
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
        $segment = $this->repo->createNew($request->all());

        if (!$segment) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->setStatusCode(201)
            ->respond($segment);
    }

    /**
     * Display the specified resource.
     *
     * @SWG\Get(
     *     path="/segment/{id}",
     *     summary="Retrieve a specific segment",
     *     tags={"segment"},
     *     description="You can retrieve a segment by it's _id or email",
     *     operationId="segment.show",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the segment to be retrieved",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Segment data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/Segment"),
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
        $segment = $this->repo->findExisting($id);

        return $this->responseBuilder
            ->respond($segment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @SWG\Put(
     *     path="/segment/{id}",
     *     summary="Updates a segment",
     *     tags={"segment"},
     *     description="Updates a segment entity.",
     *     operationId="segment.update",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the segment to be updated",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="segment",
     *         in="body",
     *         description="Values that will overwrite the attributes of the segment if the id on the url.",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/Segment",
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="The updated segment in it's new state.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/Segment"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Segment not found. A segment with the given id was not found to be updated.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request. If was not possible to update the segment.",
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
        $segment = $this->repo->findExisting($id);

        if (!$this->repo->updateExisting($segment, $request->all())) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond($segment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @SWG\Delete(
     *     path="/segment/{id}",
     *     summary="Deletes an existing segment",
     *     tags={"segment"},
     *     description="Deletes a segment.",
     *     operationId="segment.delete",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the segment to be deleted",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Segment not found. A segment with the given id was not found to be deleted.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request. If was not possible to delete the segment.",
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
        $segment = $this->repo->findExisting($id);

        if (!$this->repo->deleteExisting($segment)) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond();
    }
}
