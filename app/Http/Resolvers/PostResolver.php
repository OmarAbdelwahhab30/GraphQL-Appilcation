<?php

namespace App\Http\Resolvers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostResolver extends Controller
{
    public function index()
    {

    }

    public function store($root, $args)
    {
        $userID = auth('sanctum')->id();

        $post = Post::create([
            'title' => $args['title'],
            'description' => $args['description'],
            'user_id' => $userID,
        ]);

        return $post;
    }

    public function show($_, $args)
    {
        return Post::find($args['id']);
    }

    public function update($_, array $args)
    {
        $post = Post::findOrFail($args['id']);

        $data = array_filter([
            'title'       => $args['title'] ?? null,
            'description' => $args['description'] ?? null,
        ], fn ($v) => !is_null($v));

        $post->update($data);

        return $post->refresh();
    }

    public function destroy($_, $args)
    {
        if (Post::destroy($args['id'])){
            return [
                '__typename' => 'Message',
                'message' =>'Post deleted',
            ];
        }
        return [
            '__typename' => 'Error',
            'message' => 'Error deleting Post',
        ];
    }
}
