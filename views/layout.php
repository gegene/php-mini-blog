<?php
$flash = $flash ?? null;
$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? SITE_NAME, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header class="topbar">
        <div class="container topbar-inner">
            <a href="index.php?page=home" class="brand"><?= SITE_NAME ?></a>
            <nav class="nav">
                <a href="index.php?page=home">Accueil</a>
                <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                    <a href="index.php?page=admin-posts">Administration</a>
                <?php endif; ?>

                <?php if ($currentUser): ?>
                    <span>Bienvenue, <?= htmlspecialchars($currentUser['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <form method="post" action="index.php?page=home" style="display:inline;">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="btn btn-secondary">Déconnexion</button>
                    </form>
                <?php else: ?>
                    <a href="index.php?page=login">Connexion</a>
                    <a href="index.php?page=register">Inscription</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="page">
        <div class="container">
            <?php if ($flash): ?>
                <div class="alert alert-<?= htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <?php include $viewFile; ?>
        </div>
    </main>
</body>
</html>
