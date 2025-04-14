<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture de Commande #{{ $commande->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header, .footer { text-align: center; }
        .content { margin: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
    </style>
</head>
<body>
<div class="header">
    <h2>Librairie en Ligne</h2>
    <p>Facture de Commande</p>
</div>
<div class="content">
    <p><strong>Commande ID :</strong> {{ $commande->id }}</p>
    <p><strong>Date :</strong> {{ $commande->created_at->format('d/m/Y') }}</p>
    <p><strong>Client :</strong> {{ $commande->user->name }} ({{ $commande->user->email }})</p>
    <hr>
    <h4>Détails de la Commande :</h4>
    <table>
        <thead>
        <tr>
            <th>Livre</th>
            <th>Quantité</th>
            <th>Prix Unitaire</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($commande->livres as $livre)
            <tr>
                <td>{{ $livre->titre }}</td>
                <td>{{ $livre->pivot->quantite }}</td>
                <td>{{ $livre->prix }} €</td>
                <td>{{ $livre->prix * $livre->pivot->quantite }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <hr>
    <p><strong>Total Commande :</strong> {{ $commande->total }} €</p>
    <p><strong>Statut :</strong> {{ $commande->statut }}</p>
</div>
<div class="footer">
    <p>&copy; {{ date('Y') }} Librairie en Ligne. Tous droits réservés.</p>
</div>
</body>
</html>
