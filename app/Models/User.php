<?php

/**
 * 📁 EMPLACEMENT: app/Models/User.php
 * 
 * Modèle User avec support multi-associations
 */

namespace App\Models;

use App\Http\Resources\PermissionResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'permissions',
    ];

    public function getPermissionsAttribute()
    {
        return PermissionResource::collection($this->getPermissionsViaRoles());
    }

    public function getPicturesAttribute()
    {
        return url('/') . '/storage/' . $this->attributes['profil'];
    }

    // ==========================================
    // 🆕 MÉTHODES MULTI-ASSOCIATIONS
    // ==========================================

    /**
     * Relations many-to-many avec les associations
     */
    public function associations()
    {
        return $this->belongsToMany(
            Associations::class,
            'user_associations',
            'user_id',
            'association_id'
        )
        ->withPivot('is_primary', 'role_in_association')
        ->withTimestamps();
    }

    /**
     * Obtenir l'association principale de l'utilisateur
     */
    public function primaryAssociation()
    {
        return $this->belongsToMany(
            Associations::class,
            'user_associations',
            'user_id',
            'association_id'
        )
        ->withPivot('is_primary', 'role_in_association')
        ->wherePivot('is_primary', true)
        ->withTimestamps();
    }

    /**
     * Obtenir l'association principale (attribut)
     */
    public function getPrimaryAssociationAttribute()
    {
        return $this->primaryAssociation()->first();
    }

    /**
     * Vérifier si l'utilisateur a accès à une association
     */
    public function hasAccessToAssociation($associationId): bool
    {
        return $this->associations()->where('association_id', $associationId)->exists();
    }

    /**
     * Attacher une association à l'utilisateur
     */
    public function attachAssociation($associationId, $isPrimary = false, $role = null)
    {
        // Si c'est la première association, la définir comme principale
        if ($this->associations()->count() === 0) {
            $isPrimary = true;
        }

        // Si on veut la définir comme principale, retirer le statut des autres
        if ($isPrimary) {
            $this->associations()->updateExistingPivot(
                $this->associations()->pluck('association_id')->toArray(),
                ['is_primary' => false]
            );
        }

        // Attacher l'association
        if (!$this->hasAccessToAssociation($associationId)) {
            $this->associations()->attach($associationId, [
                'is_primary' => $isPrimary,
                'role_in_association' => $role,
            ]);
        }
    }

    /**
     * Détacher une association
     */
    public function detachAssociation($associationId)
    {
        $this->associations()->detach($associationId);

        // Si aucune association principale, définir la première comme principale
        if (!$this->primaryAssociation()->exists() && $this->associations()->count() > 0) {
            $firstAssociation = $this->associations()->first();
            $this->associations()->updateExistingPivot($firstAssociation->id, ['is_primary' => true]);
        }
    }

    /**
     * Définir une association comme principale
     */
    public function setPrimaryAssociation($associationId)
    {
        if ($this->hasAccessToAssociation($associationId)) {
            // Retirer le statut primary de toutes les associations
            $this->associations()->updateExistingPivot(
                $this->associations()->pluck('association_id')->toArray(),
                ['is_primary' => false]
            );

            // Définir la nouvelle comme principale
            $this->associations()->updateExistingPivot($associationId, ['is_primary' => true]);
        }
    }

    /**
     * Obtenir les IDs des associations de l'utilisateur
     */
    public function getAssociationIdsAttribute()
    {
        return $this->associations()->pluck('association_id')->toArray();
    }
}