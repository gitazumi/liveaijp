<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportComment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'report_id',
        'comment_text',
    ];

    /**
     * Get the report that owns the comment.
     */
    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
