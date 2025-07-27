<p>Bonjour {{ $user->first_name }},</p>

<p>Votre compte a été créé avec succès. Voici vos identifiants :</p>

<ul>
    <li><strong>Email :</strong> {{ $user->email }}</li>
    <li><strong>Mot de passe temporaire :</strong> {{ $password }}</li>
</ul>

<p>Merci de changer ce mot de passe dès votre première connexion.</p>
