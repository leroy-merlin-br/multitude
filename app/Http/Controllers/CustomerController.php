<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Leadgen\Customer\Repository;
use Leadgen\Segment\RulesetPreviewService;

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
     *     @SWG\Parameter(
     *         name="query",
     *         in="query",
     *         description="Segment query to filter customers. In json format",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Customer data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", type="array", @SWG\Items(ref="#/definitions/Customer")),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     )
     * )
     *
     * @param Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RulesetPreviewService $previewService)
    {
        $page = $request->get('page', 1);

        if ($query = $request->get('query', null)) {
            $query = json_decode(urldecode($query), true);

            if (empty($query)) {
                return $this->responseBuilder
                    ->respondBadRequest($query, ['Empty or malformated \'query\' parameter']);
            }

            return $this->responseBuilder
                ->respond($previewService->preview($query));
        }

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
     *     description="You can retrieve a customer by it's _id or email",
     *     operationId="customer.show",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="_id or email of the customer to be retrieved",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Customer data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/Customer"),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
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
        $id = urldecode($id);

        if (false !== strpos($id, '@')) {
            $id = ['email' => $id];
        }

        $interactionType = $this->repo->findExisting($id);

        return $this->responseBuilder
            ->respond($interactionType);
    }
}
