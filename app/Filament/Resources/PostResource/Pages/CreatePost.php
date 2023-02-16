<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        if ($data['tags']) {
            $post = Post::create($data);

            foreach ($data['tags'] as $tagId) {
                DB::table('post_tag')->insert([
                    'tag_id' => $tagId,
                    'post_id' => $post->id,
                ]);
            }
        }

        return $data;
    }
}
