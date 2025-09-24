<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectApplicant extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'freelancer_id',
        'project_id',
        'status',
        'message' 
    ];
    public function freelancer()
    {
        return $this->belongsTo(User::class, 'freelancer_id');
        // pengecekan freelancer ini adalah user apa
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
        // pengecekan pelamar ini melamar pada project apa saja
    }
}
