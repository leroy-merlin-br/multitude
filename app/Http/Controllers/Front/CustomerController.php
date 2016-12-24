<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Infrastructure\Search\ElasticsearchCursor;
use Leadgen\Customer\Customer;
use Leadgen\Segment\RulesetPreviewService;

/**
 * Handles the requests regarding Customers with a user facing front-end
 */
class CustomerController
{
    /**
     * Display a listing of the resource.
     *
     * @param Request               $request        Client request.
     * @param RulesetPreviewService $previewService Process an es query if necessary.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, RulesetPreviewService $previewService)
    {
        $apiResponse = $this->api()->index($request, $previewService)->getOriginalContent();

        $viewVars = [
            'customers' => $apiResponse['content'],
            'customerTotal' => $apiResponse['content'] instanceof ElasticsearchCursor ? $apiResponse['content']->countPossible() : Customer::all()->count(),
        ];

        return view('app.customer.index', $viewVars);
    }

    /**
     * Show the given segment
     *
     * @param string $id Id of the segment being showed.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $apiResponse = $this->api()->show($id)->getOriginalContent();

        $apiResponse['customer'] = $apiResponse['content'];

        return view('app.customer.show', $apiResponse);
    }

    /**
     * Returns the API Segment controller.
     *
     * @return \App\Http\Controllers\CustomerController
     */
    protected function api()
    {
        return app()->make(\App\Http\Controllers\CustomerController::class);
    }
}
