<?php

namespace App\Models;

use App\Models\Scopes\ModelScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offrande extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new ModelScope);
    }

    public function associations()
    {
        return $this->belongsTo(Associations::class, 'association_id');
    }
}
