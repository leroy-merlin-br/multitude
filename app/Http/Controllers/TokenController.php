<?php

namespace App\Http\Controllers;

use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Leadgen\Authorization\Repository;
use Leadgen\Authorization\Token;

/**
 * REST Api Controller for Auth Tokens
 */
class TokenController extends ApiController
{
    /**
     * Authorization Token repository.
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
     * @param Repository      $repo            Token repository.
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
     *     path="/authTokens",
     *     summary="Retrieve a list of authTokens",
     *     tags={"authToken"},
     *     description="Retrieves a list of authTokens with pagination support.",
     *     operationId="authToken.index",
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
     *         description="List of existent authTokens",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", type="array", @SWG\Items(ref="#/definitions/AuthToken")),
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
     *     path="/authTokens",
     *     summary="Creates a new authToken",
     *     tags={"authToken"},
     *     description="Creates a new authToken entity.",
     *     operationId="authToken.store",
     *     @SWG\Parameter(
     *         name="authToken",
     *         in="body",
     *         description="AuthToken to be created",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/AuthToken",
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="The newly created authToken",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/AuthToken"),
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
        $authToken = $this->repo->createNew($request->all());

        if (!$authToken) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->setStatusCode(201)
            ->respond($authToken);
    }

    /**
     * Display the specified resource.
     *
     * @SWG\Get(
     *     path="/authTokens/{id}",
     *     summary="Retrieve a specific authToken",
     *     tags={"authToken"},
     *     description="You can retrieve an authToken by it's _id or email",
     *     operationId="authToken.show",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the authToken to be retrieved",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="AuthToken data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/AuthToken"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="AuthToken not found",
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
        $authToken = $this->repo->findExisting($id);

        return $this->responseBuilder
            ->respond($authToken);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @SWG\Delete(
     *     path="/authTokens/{id}",
     *     summary="Deletes an existing authToken",
     *     tags={"authToken"},
     *     description="Deletes an authToken.",
     *     operationId="authToken.delete",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the authToken to be deleted",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="AuthToken not found. An authToken with the given id was not found to be deleted.",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Bad request. If was not possible to delete the authToken.",
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
        $authToken = $this->repo->findExisting($id);

        if (!$this->repo->deleteExisting($authToken)) {
            return $this->responseBuilder
                ->respondBadRequest(null, $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->respond();
    }
}
