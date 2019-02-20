<?php

namespace Tests\Feature;

use App\Comment;
use App\Photo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PhotoDetailApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJsonを返却する()
    {
        factory(Photo::class)->create()->each(function ($photo){
            $photo->comments()->saveMany(factory(Comment::class, 3)->make());
        });
        $photo = Photo::first();

        $comments = $photo->comments()->get();
        
        $response = $this->json('GET', route('photo.show', [
            'id' => $photo->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'user' => [
                    'name' => $photo->user->name,
                ],
                'liked_by_user' => false,
                'likes_count' => 0,
                'comments' => [
                    'content' => $comments->content,
                    'author' => [
                        'name' => $comments->user->name,
                    ],
                ]
                ->all(),
            ]);
    }
}
