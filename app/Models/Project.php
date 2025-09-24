<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'category_id',
        'client_id',
        'budget',
        'skill_level',
        'has_finished',
        'has_started'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
        // pengecekan projek ini dimiliki oleh category apa
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
        // pengecekan projek ini dimiliki oleh user apa
    }
    public function tools()
    {
        return $this->belongsToMany(Tool::class, 'project_tools', 'project_id', 'tool_id')->wherePivotNotNull('deleted_at')->withPivot('id');
        // menampilkan semua tool yang dipakai pada project ini
        // bisa juga menghapus tool pada project ini (pada pivot table project_tools ada kolom deleted_at)
    }

    public function applicants()
    {
        return $this->hasMany(ProjectApplicant::class);
        // menampilkan semua pelamar pada project ini
    }
}
