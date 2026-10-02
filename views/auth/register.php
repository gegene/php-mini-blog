<?php
$currentUser = $auth->getCurrentUser();
?>
<div class="card">
    <h1>Inscription</h1>
    <form method="post" action="index.php?page=register" class="form-grid">
        <input type="hidden" name="action" value="register">

        <div>
            <label for="username">Nom d'utilisateur</label>
            <input id="username" type="text" name="username" required>
        </div>

        <div>
            <label for="email">Adresse e-mail</label>
            <input id="email" type="email" name="email" required>
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required>
        </div>

        <div>
            <label for="password_confirm">Confirmer le mot de passe</label>
            <input id="password_confirm" type="password" name="password_confirm" required>
        </div>

        <button type="submit" class="btn">S'inscrire</button>
    </form>

    <p style="margin-top: 16px;">
        Déjà inscrit ? <a href="index.php?page=login">Se connecter</a>
    </p>
</div>
