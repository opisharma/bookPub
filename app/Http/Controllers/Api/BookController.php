<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class BookController extends Controller
{
    public function store(Request $request){
        $request->validate([
            "name" => "required|string",
            "isbn" => "required|string",
            "author" => "required|string",
            "category" => "required|string"
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        $book = new Book([
            "name" => $request->name,
            "isbn" => $request->isbn,
            "author" => $request->author,
            "category" => $request->category,
            "user_id" => $request->id
        ]);

        $book->save();

        return response()->json($book, 201);
    }


    public function read(){
        $user = JWTAuth::parseToken()->authenticate();
        $books = Book::where('user_id', $user->id)->get();

    }

    public function update(Request $request, $id){
        $book = Book::findOrFail($id);
        $user = JWTAuth::parseToken()->authenticate();

        if($book->user_id != $user->id){
            return response()->json(['error' => 'Focus on editing your own books'],403);
        }

        $book->update($request->all());
        return response()->json($book);
    }
}
