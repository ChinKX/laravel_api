<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\AuthorResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\SaveAuthorRequest;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new AuthorCollection(Author::with('books')->paginate(2));
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
            return new AuthorResource(Author::with('books')->findOrFail($id));
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
    public function store(SaveAuthorRequest $request)
    {
        try {
            if ($request->validated()) {
                $author = Author::create($request->all());

                $author->books()->sync($request->get('books'));

                return new AuthorResource($author);
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
    public function update(SaveAuthorRequest $request, $id)
    {
        try {
            if ($request->validated()) {
                $author = Author::findOrFail($id);
                $author->update($request->all());

                $author->books()->sync($request->get('books'));

                return new AuthorResource($author);
            }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $author = Author::findOrFail($id);
            $author->delete();

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
