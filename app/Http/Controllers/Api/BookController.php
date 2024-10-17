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
}
