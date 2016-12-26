<?php

namespace App\Http\Controllers;

use App\Http\ResponseBuilder;
use Illuminate\Http\Request;
use Leadgen\Customer\Customer;
use Leadgen\Interaction\Interaction;
use Leadgen\Interaction\Repository;

/**
 * Controller to handle the creation of Interactions.
 */
class InteractionController extends ApiController
{
    /**
     * Interaction repository.
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
     * @param Repository      $repo            Interaction repository.
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
     *     path="/interaction/pulse",
     *     summary="Retrieve a list of interactions of the last minute",
     *     tags={"interaction"},
     *     description="Retrieves a list of interactions of the last minute.",
     *     operationId="interaction.pulse",
     *     @SWG\Response(
     *         response=200,
     *         description="Summary of latest interactions",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", type="array", @SWG\Items(type="object"), description="Author as key and interaction type as value."),
     *             @SWG\Property(property="errors", type="array", @SWG\Items(type="string"), description="Array of error messages"),
     *         ),
     *     ),
     * )
     *
     * @return \Illuminate\Http\Response
     */
    public function pulse()
    {
        $viewVars = [
            'interactionCount' => Interaction::all()->count(),
            'customerCount' => Customer::all()->count(),
            'interactionPulse' => []
        ];

        if ($interactionPulse = app()->make('redis')->get('interactionPulse')) {
            $interactionPulse = unserialize($interactionPulse);

            $viewVars['interactionPulse'] = $interactionPulse;
        }

        return $this->responseBuilder
                ->respond($viewVars);
    }

    /**
     * Stores a new resource.
     *
     * @SWG\Post(
     *     path="/interaction",
     *     summary="Stores a new interaction",
     *     tags={"interaction"},
     *     description="Stores a new customer interaction.",
     *     operationId="interaction.store",
     *     @SWG\Parameter(
     *         name="interaction",
     *         in="body",
     *         description="Interaction to be stored",
     *         required=true,
     *         @SWG\Schema(
     *             ref="#/definitions/Interaction",
     *         )
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Interaction data",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="string", description="Response status"),
     *             @SWG\Property(property="content", ref="#/definitions/Interaction"),
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
        $interaction = $this->repo->createNew($request->all());

        if (!$interaction) {
            return $this->responseBuilder
                ->respondBadRequest($request->all(), $this->repo->getLastErrors());
        }

        return $this->responseBuilder
            ->setStatusCode(201)
            ->respond($interaction);
    }
}
