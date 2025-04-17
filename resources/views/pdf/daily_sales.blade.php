<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ventes journalières</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #444; padding: 6px; text-align: center; }
        th { background: #eee; }
    </style>
</head>
<body>
<h2 style="text-align:center;">Ventes journalières — {{ today()->format('d/m/Y') }}</h2>
<table>
    <thead>
    <tr>
        <th>ID Commande</th>
        <th>ID Client</th>
        <th>Montant (FCFA)</th>
        <th>Date de paiement</th>
    </tr>
    </thead>
    <tbody>
    @foreach($ventes as $commande)
        <tr>
            <td>{{ $commande->id }}</td>
            <td>{{ $commande->client_id }}</td>
            <td>{{ number_format($commande->total, 2, ',', ' ') }}</td>
            <td>{{ $commande->updated_at->format('d/m/Y H:i') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
