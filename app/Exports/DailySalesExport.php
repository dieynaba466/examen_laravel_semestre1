<?php

namespace App\Exports;

use App\Models\Commande;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailySalesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Commande::whereDate('updated_at', today())
            ->where('statut', 'Payée')
            ->get(['id', 'user_id', 'total', 'updated_at']);
    }

    public function headings(): array
    {
        return [
            'ID Commande',
            'ID Client',
            'Montant (FCFA)',
            'Date de paiement',
        ];
    }
}
