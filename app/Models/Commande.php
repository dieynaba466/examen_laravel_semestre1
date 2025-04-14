<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'statut',
        'total',
        'date_paiement',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livres()
    {
        return $this->belongsToMany(Livre::class)->withPivot('quantite');
    }
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

}
