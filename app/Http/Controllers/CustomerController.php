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
}
