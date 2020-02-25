<?php

namespace App\Http\Controllers;

use App\Http\Resources\PublisherCollection;
use Illuminate\Http\Request;
use App\Publisher;
use App\Http\Resources\PublisherResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\SavePublisherRequest;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PublisherCollection(Publisher::with('books')->paginate(2));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            return new PublisherResource(Publisher::with('books')->findOrFail($id));
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SavePublisherRequest $request)
    {
        try {
            if ($request->validated()) {
                $publisher = Publisher::create($request->all());

                return new PublisherResource($publisher);
            }
        } catch (QueryException $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SavePublisherRequest $request, $id)
    {
        try {
            if ($request->validated()) {
                $publisher = Publisher::findOrFail($id);
                $publisher->update($request->all());

                return new PublisherResource($publisher);
            }
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 404);
        } catch (QueryException $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $publisher = Publisher::findOrFail($id);
            $publisher->delete();

            return 204;
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 404);
        } catch (QueryException $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }
}
