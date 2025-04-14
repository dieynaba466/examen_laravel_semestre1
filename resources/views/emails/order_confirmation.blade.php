<!DOCTYPE html>
<html>
<head>

    <title>Confirmation de Commande</title>
</head>
<body>
<h1>Merci pour votre commande, {{ $commande->client->name }} !</h1>
<p>Votre commande #{{ $commande->id }} a été confirmée.</p>
<p>Voici les détails :</p>
<ul>
    @foreach ($commande->livres as $livre)
        <li>{{ $livre->titre }} - {{ $livre->pivot->quantite }} x {{ $livre->prix }} €</li>
    @endforeach
</ul>
<p>Total : {{ $commande->total }} €</p>
<p>Nous vous enverrons un email une fois votre commande expédiée.</p>
</body>
</html>
