<!DOCTYPE html>
<html>
<head>
    <title>Réinitialisation de mot de passe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Réinitialisation de votre mot de passe</h1>

    <p>Bonjour,</p>

    <p>Vous recevez cet email car vous (ou quelqu'un d'autre) avez demandé la réinitialisation de votre mot de passe pour votre compte sur <?= site_url() ?>.</p>

    <p>Pour réinitialiser votre mot de passe, veuillez cliquer sur le bouton ci-dessous :</p>

    <p>
        <a href="<?= $resetUrl ?>" class="btn">Réinitialiser mon mot de passe</a>
    </p>

    <p>Si vous n'avez pas demandé cette réinitialisation, veuillez ignorer cet email et aucune action ne sera prise.</p>

    <p>Si le bouton ne fonctionne pas, vous pouvez également copier et coller l'URL suivante dans votre navigateur :</p>

    <p><?= $resetUrl ?></p>

    <p>Ce lien expirera dans 1 heure.</p>

    <p>Cordialement,<br>L'équipe </p>
</div>
</body>
</html>