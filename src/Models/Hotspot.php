<?php

namespace Bigmom\VeEditor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'scene_id', 'position', 'name', 'meta', 'medias', 'created_at', 'updated_at'];

    public function scene()
    {
        return $this->belongsTo(Scene::class);
    }

    public function folders()
    {
        return $this->belongsToMany(Folder::class);
    }

    public function getMediasJsonAttribute()
    {
        $medias = collect(preg_split('/(\n|\r\n)/', $this->medias))->filter(function ($media) {
            return $media;
        });
        $thumbnails = $medias->filter(function ($media) { return preg_match('/Thumbnail/i', $media); })->values();
        $inactives = $medias->filter(function ($media) { return preg_match('/Inactive/i', $media); })->values();
        $descriptions = $medias->filter(function ($media) { return preg_match('/Description/i', $media); })->values();
        $images = $medias->filter(function ($media) { return !preg_match('/Thumbnail/i', $media) && !preg_match('/Inactive/i', $media) && !preg_match('/Description/i', $media); })->values();

        $images = $images->map(function ($image, $i) use ($thumbnails, $inactives, $descriptions) {
            $content_type = explode(':', $image, 2)[0] ?? '';
            $content_path = explode(':', $image, 2)[1] ?? '';
            $thumbnail_path = explode(':', $thumbnails->get($i) ?? '', 2)[1] ?? '';
            $inactive_path = explode(':', $inactives->get($i) ?? '', 2)[1] ?? '';
            $description = explode(':', $descriptions->get($i) ?? '', 2)[1] ?? '';
            return compact('content_path', 'content_type', 'thumbnail_path', 'inactive_path', 'description');
        });

        return $images;
    }
}
