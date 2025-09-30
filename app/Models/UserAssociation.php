<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAssociation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'association_id',
        'is_primary',
        'role_in_association',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relation avec User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec Association
     */
    public function association()
    {
        return $this->belongsTo(Associations::class);
    }

    /**
     * Définir comme association principale
     */
    public function setAsPrimary()
    {
        // Retirer le statut primary des autres associations de cet utilisateur
        self::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Définir celle-ci comme principale
        $this->update(['is_primary' => true]);
    }

    /**
     * Scope pour les associations principales
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}