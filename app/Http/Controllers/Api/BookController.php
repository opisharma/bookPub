<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class BookController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'isbn' => 'required|string',
            'author' => 'required|string',
            'category' => 'required|string',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        $book = new Book([
            'name' => $request->name,
            'isbn' => $request->isbn,
            'author' => $request->author,
            'category' => $request->category,
            'user_id' => $user->id
        ]);

        $book->save();

        return response()->json($book, 201);
    }


    public function read(){
        $user = JWTAuth::parseToken()->authenticate();
        $books = Book::where('user_id', $user->id)->get();

        return response()->json($books);


    }

  

    public function update(Request $request, $id) {
        $book = Book::findOrFail($id);
        $user = JWTAuth::parseToken()->authenticate();

        if ($book->user_id != $user->id) {
            return response()->json(['error' => 'Edit your own books.'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string',
            'isbn' => 'sometimes|string',
            'author' => 'sometimes|string',
            'category' => 'sometimes|string',
        ]);

        $book->update($request->only(['name', 'isbn', 'author', 'category']));

        return response()->json($book);
    }

    public function destroy($id){

        $book = Book::findOrFail($id);
        $user = JWTAuth::parseToken()->authenticate();

        if($book->user_id != $user->id){
            return response()->json(['error' => 'Delete your own books'],403);
        }

        $book->delete();

        return response()->json(['message' => 'Deleted Successfully'],200);
    }

    public function dashboard(Request $request){
        $query = Book::query();

        if($request->has('category')){
            $query-> where('category', $request->category);
        }

        if($request->has('search')){
            $search = $request->search;
            $query->where(function($q) use ($search){

                $q->where('name', 'LIKE', "%$search%")->orWhere('author', 'LIKE', "%$search%");
            });
        }

        $books = $query->get();
        return response()->json($books);
    }
}
