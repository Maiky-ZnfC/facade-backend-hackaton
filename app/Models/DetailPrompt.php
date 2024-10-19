<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPrompt extends Model {
    use HasFactory;

    protected $fillable = ['id_prompt', 'role', 'content'];

    public function prompt() {
        return $this->belongsTo(Prompt::class, 'id_prompt');
    }
}
