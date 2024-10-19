<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prompt extends Model {
    use HasFactory;

    protected $fillable = ['title', 'user_id'];

    public function detailPrompts() {
        return $this->hasMany(DetailPrompt::class, 'id_prompt');
    }
}
