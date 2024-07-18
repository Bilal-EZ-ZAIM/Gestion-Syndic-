<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur notre site</title>
</head>

<body>
    <h1>Bienvenue {{ $data['username'] }} !</h1>
    <p>Merci de vous être inscrit sur notre site. Nous sommes heureux de vous accueillir parmi nous.</p>
    <p>Vous pouvez maintenant vous connecter en utilisant les informations suivantes :</p>
    <p><strong>Nom d'utilisateur :</strong> {{ $data['username'] }}</p>
    <p><strong>Mot de passe :</strong> {{ $data['password'] }}</p>
    <p>Nous vous souhaitons une expérience agréable sur notre site !</p>
    <p>Cordialement,<br>L'équipe de support</p>
    <p>Si vous souhaitez vous connecter maintenant, veuillez <a href="http://127.0.0.1:8000/login">cliquer ici</a>.</p>
</body>

</html>
