<!DOCTYPE html>
<html>
<head>
    <title>Reçu de paiement</title>
</head>
<body>
<h1>Bonjour {{ $commande->user->name }},</h1>
<p>Votre paiement a été reçu avec succès. Voici les détails :</p>
<ul>
    <li><strong>Numéro de commande :</strong> {{ $commande->id }}</li>
    <li><strong>Montant payé :</strong> {{ number_format($commande->total, 2) }} €</li>
    <li><strong>Statut :</strong> Payé</li>
    <li><strong>Date du paiement :</strong> {{ $commande->date_paiement }}</li>
</ul>
<p>Merci pour votre achat. Nous vous enverrons une notification lorsque votre commande sera expédiée.</p>
<p>Bonne journée !</p>
</body>
</html>
