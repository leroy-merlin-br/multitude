<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Leadgen\Customer\Repository;

/**
 * Controller for Customers entity.
 */
class CustomerController extends ApiController
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
     * @SWG\Get(
     *     path="/customer",
     *     summary="Retrieve a list of customers",
     *     tags={"customer"},
     *     description="Retrieves a list of customers with pagination support.",
     *     operationId="customer.index",
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
     *         description="Customer data",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Customer")
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
     * Retrieve a specific customer.
     *
     * @SWG\Get(
     *     path="/customer/{id}",
     *     summary="Retrieve a specific customer",
     *     tags={"customer"},
     *     description="You can retrieve a customer by it's _id.",
     *     operationId="customer.show",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id of the customer to be retrieved",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Customer data",
     *         @SWG\Schema(
     *             ref="#/definitions/Customer",
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="404",
     *         description="Customer not found",
     *     )
     * )
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
}
