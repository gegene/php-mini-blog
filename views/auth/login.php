<?php
$posts = $posts ?? [];
$currentUser = $auth->getCurrentUser();
?>
<div class="card">
    <h1>Connexion</h1>
    <form method="post" action="index.php?page=login" class="form-grid">
        <input type="hidden" name="action" value="login">

        <div>
            <label for="email">Adresse e-mail</label>
            <input id="email" type="email" name="email" required>
        </div>

        <div>
            <label for="password">Mot de passe</label>
            <input id="password" type="password" name="password" required>
        </div>

        <button type="submit" class="btn">Se connecter</button>
    </form>

    <p style="margin-top: 16px;">
        Pas encore inscrit ? <a href="index.php?page=register">Créer un compte</a>
    </p>
</div>
