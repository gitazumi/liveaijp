<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scam_types',
        'description',
        'evidence_files',
        'edit_token',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scam_types' => 'array',
        'evidence_files' => 'array',
    ];

    /**
     * Get the comments for the report.
     */
    public function comments()
    {
        return $this->hasMany(ReportComment::class);
    }
}
