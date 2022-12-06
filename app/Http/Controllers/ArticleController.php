<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Articles;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ListArticleResource;
use App\Http\Resources\DetailArticleResource;

class ArticleController extends Controller
{
    public function index()
    {
        $article = Article::all();
        return ListArticleResource::collection(Article::paginate(3));
    }

    public function show($id)
    {

        $data = Article::with('kategori:id,name')->findOrFail($id);
        return new DetailArticleResource($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'file' => 'required|image|mimes:jpeg,png,jpg',
            'user_id' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        if ($request->file) {
            $fileName = $this->generateRandomString();
            $extension = $request->file->extension();
            Storage::putFileAs('images', $request->file, $fileName . '.' . $extension);
        }

        $request['image'] = $fileName . '.' . $extension;
        $create_articles = Article::create($request->all());
        return response()->json(['Category created successfully.', new ListArticleResource($create_articles)]);
    }

    public function update(ArticleRequest $request, $id)
    {

        // if ($request->file) {
        //     $fileName = $this->generateRandomString();
        //     $extension = $request->file->extension();
        //     Storage::putFileAs('images', $request->file, $fileName . '.' . $extension);
        //     $upadateImage = $fileName . '.' . $extension;
        // }

        // $request['image'] = $upadateImage;
        $validasi_data = Article::findOrFail($id);
        $validasi_data->update($request->validated());
        return response()->json(['Articles update successfuly.', new ListArticleResource($validasi_data->loadMissing(['kategori:id,name']))], 200);
    }

    public function destroy($id)
    {
        $deleteArticles = Article::findOrFail($id);
        $deleteArticles->delete();
        return response()->json(['Article deleted.']);
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
