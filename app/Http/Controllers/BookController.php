<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\SaveBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request // contain filter params
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $isbn = $request->input('isbn');
        $title = $request->input('title');
        $year = $request->input('year');
        $author = $request->input('author');
        $publisher = $request->input('publisher');
        // info($author);
        // info($publisher);

        $books = Book::with(['authors', 'publisher'])
            ->when($isbn, function($query) use($isbn) {
                $query->where('isbn', $isbn);
            })
            ->when($title, function($query) use($title) {
                $query->where('title', 'like', "%$title%");
            })
            ->when($year, function($query) use($year) {
                $query->where('year', $year);
            })
            ->when($author, function($query) use($author) {
                $query->whereHas('authors', function($query) use($author) {
                    $query->where('name', $author);
                });
            })
            ->when($publisher, function($query) use($publisher) {
                $query->whereHas('publisher', function($query) use($publisher) {
                    $query->where('name', $publisher);
                });
            })
            ->paginate(2);

        return new BookCollection($books);
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
            return new BookResource(Book::with(['authors', 'publisher'])->findOrFail($id));
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
    public function store(SaveBookRequest $request)
    {
        try {
            if ($request->validated()) {
                $book = Book::create($request->all());

                $book->authors()->sync($request->get('authors'));

                return new BookResource($book);
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
    public function update(SaveBookRequest $request, $id)
    {
        try {
            if ($request->validated()) {
                $book = Book::findOrFail($id);
                $book->update($request->all());

                $book->authors()->sync($request->get('authors'));

                return new BookResource($book);
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
            $book = Book::findOrFail($id);
            $book->delete();

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
