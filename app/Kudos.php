<?php

namespace App;

use App\Traits\SetUserBeforeCreateEntryTrait;
use Jenssegers\Mongodb\Eloquent\Model;

class Kudos extends Model
{
    use SetUserBeforeCreateEntryTrait;

    protected $userIdField = 'user_from_id';

    protected $fillable = [
        'text',
        'user_from_id',
        'user_to_id',
        'kudos_category_id',
    ];

    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
    ];

    public function userFrom()
    {
        return $this->belongsTo(User::class, 'user_from_id');
    }

    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_to_id');
    }

    public function kudosCategory()
    {
        return $this->belongsTo(KudosCategory::class, 'kudos_category_id');
    }

    public function kudosTags()
    {
        return $this->belongsToMany(KudosTag::class);
    }

    public function syncTags(array $tags) {
        $tagsIds = [];

        foreach ($tags as $tag) {
            $tagsIds[] = KudosTag::firstOrCreate(['name' => $tag])->id;
        }

        $this->kudosTags()->sync($tagsIds);
    }
}
