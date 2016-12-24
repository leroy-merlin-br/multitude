<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Leadgen\Segment\Repository;
use Leadgen\Segment\Segment;
use Mongolid\Cursor\EmbeddedCursor;
use Leadgen\Customer\Repository as CustomerRepository;

/**
 * Handles the requests regarding Segments with a user facing front-end
 */
class SegmentController
{
    /**
     * Segment repository.
     *
     * @var Repository
     */
    protected $repo;

    /**
     * Constructor.
     *
     * @param Repository $repo Segment repository.
     */
    public function __construct(Repository $repo)
    {
        $this->repo = $repo;
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
        $apiResponse = $this->api()->index($request)->getOriginalContent();

        $apiResponse['segments'] = $apiResponse['content'];

        return view('app.segment.index', $apiResponse);
    }

    /**
     * Shows a form to te user to create a new segment
     *
     * @param Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $viewVars = [
            'segment' => new Segment,
            'formSubmit' => route('front.segment.store')
        ];

        return view('app.segment.create', $viewVars);
    }

    /**
     * Stores the given segment
     *
     * @param Request $request Client request.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->api()->store($request)->getOriginalContent();
    }

    /**
     * Show the given segment
     *
     * @param CustomerRepository $customerRepo Instance that will be used to retrieve sample customers of the segment.
     * @param string             $id           Id of the segment being showed.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerRepository $customerRepo, string $id)
    {
        $apiResponse = $this->api()->show($id)->getOriginalContent();

        $segment = $apiResponse['content'];

        $viewVars = [
            'segment' => $segment,
            'customers' => $customerRepo->where(['segments' => $segment->slug], 1, -1)
        ];

        return view('app.segment.show', $viewVars);
    }

    /**
     * Shows the edit form for the given segment
     *
     * @param string $id Id of the segment being edited.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $apiResponse = $this->api()->show($id)->getOriginalContent();

        $apiResponse['segment'] = $apiResponse['content'];
        $apiResponse['formSubmit'] = route('front.segment.update', ['id' => $id]);
        $apiResponse['formAction'] = 'put';

        return view('app.segment.edit', $apiResponse);
    }

    /**
     * Update an segment
     *
     * @param Request $request Client request.
     * @param string  $id      Id of the segment being updated.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        return $this->api()->update($request, $id)->getOriginalContent();
    }

    /**
     * Destroy the given segment
     *
     * @param string $id Id of the segment being deleted.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id)
    {
    }

    /**
     * Returns the API Segment controller.
     *
     * @return \App\Http\Controllers\SegmentController
     */
    protected function api()
    {
        return app()->make(\App\Http\Controllers\SegmentController::class);
    }
}
