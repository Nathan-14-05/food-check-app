<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 *"Authenticatable" est crucial : c'est ce qui permet à ce modèle
 * de gérer les connexions, les sessions et les mots de passe.
 */
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * PROTECTION $hidden (Confidentialité)
     * Ces données sont "cachées". Si l'application envoie les infos de
     * l'utilisateur (par exemple vers du JavaScript), le mot de passe
     * ne sera jamais inclus. C'est une sécurité native de Laravel.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * LES "CASTS" (Transformation de données)
     * Ici, on dit à Laravel comment traiter certaines colonnes :
     * - 'password' => 'hashed' : Indique que le mot de passe doit toujours
     * être crypté (haché) pour ne jamais apparaître en clair en base de données.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * RELATION ÉLOQUENT : hasMany (A plusieurs)
     * C'est le lien que nous avons ajouté.
     * Logique : "Un utilisateur possède plusieurs listes d'aliments".
     * User -> possède des FoodLists -> qui contiennent des Products.
     */

    public function foodLists()
    {
        return $this->hasMany(FoodList::class);
    }
}
