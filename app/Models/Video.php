<?php

namespace App\Models;

use App\Application\Uuids;
use App\Models\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use Uuids;

    protected $guarded = [];
    protected $appends = ['thumbnail_url'];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('comment_id')->orderBy('created_at', 'DESC');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function editable()
    {
        return auth()->check() && $this->channel->user_id === auth()->user()->id;
    }

    public function getThumbnailUrlAttribute()
    {
//        return Storage::url('https://content.dropboxapi.com/apitl/1/AtVLTRLz4absk7sxPx_hWfjbrdDtrnrIi-SGtE0PHmlOqJB5U3v11y7TxCh_vUVJAlsbK5YU_Ykdb7yJNkV_F3s6Vg1RlwDovGZ_d6fnvnwUb0B5UrRyQZ63CII2MiCmaxtmGokOYm7XywLVBtXbxWlshuDz1AmKww5mPUV4cdAlJFSOOrhQfHjGe1UfjZ7WUg2k3unjzCEsARJ3763OwwoT3rWeNWtYsNsD9tDh88ZfXE5WMT3Bqg0Haoh4m9n8tHXPbh91lr1PXmJMuwDU_uAdMMF48cm5UlwLT8wFCXgDCR2VNMoQgx3aEKv9VOhUeteoyGvRlWz19R6FfyCRsLNcOG4hYHZpGJx08mQ2m9SsoSyKiv01yP_4wNZfEyxX_fwvsbP-saAWidEcfdXBl7NK');
        if ($this->thumbnail)
            if (Storage::exists($this->thumbnail))
                return Storage::url($this->thumbnail);

        return 'Not found';
    }
}
