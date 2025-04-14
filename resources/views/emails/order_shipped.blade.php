<!DOCTYPE html>
<html>
<head>
    <title>Votre commande a été expédiée</title>
</head>
<body>
<h1>Bonjour {{ $commande->user->name }},</h1>
<p>Votre commande #{{ $commande->id }} a été expédiée.</p>
<p>Merci d'avoir commandé chez nous !</p>
</body>
</html>
