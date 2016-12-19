<?php

namespace App\Http\Controllers;

use App\Http\ResponseBuilder;
use Illuminate\Http\Request;

/**
 * Controller for Api Documentation.
 */
class DocsController extends ApiController
{
    /**
     * To build the server response.
     *
     * @var ResponseBuilder;
     */
    protected $responseBuilder;

    /**
     * Constructor.
     *
     * @param ResponseBuilder $responseBuilder To build the server response.
     */
    public function __construct(ResponseBuilder $responseBuilder)
    {
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * Displays the API docs.
     *
     * @param Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $viewVars = [
            'host' => $request->getHttpHost(),
        ];

        return view('app.swagger', $viewVars);
    }
}
