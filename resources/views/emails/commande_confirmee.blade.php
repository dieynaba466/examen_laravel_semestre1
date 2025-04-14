<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de votre commande</title>
</head>
<body>
<h1>Bonjour {{ $commande->user->name }},</h1>
<p>Merci pour votre commande. Voici les détails :</p>
<ul>
    <li><strong>Numéro de commande :</strong> {{ $commande->id }}</li>
    <li><strong>Total :</strong> {{ number_format($commande->total, 2) }} €</li>
    <li><strong>Statut :</strong> {{ $commande->statut }}</li>
</ul>
<p>Nous vous informerons lorsque votre commande sera expédiée.</p>
<p>Merci de votre confiance.</p>
</body>
</html>
