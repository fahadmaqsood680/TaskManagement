<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title','description','priority','due_date','category_id','assigned_to','assigned_by','status'];

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
        public function comments()
    {
        return $this->hasMany(Comment::class);
    }

}
