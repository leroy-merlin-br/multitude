<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use Leadgen\Segment\Repository;

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
        return view('app.segment.index');
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
            'search' => $request->input('search', ''),
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
}
