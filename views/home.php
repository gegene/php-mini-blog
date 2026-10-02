<?php
$posts = $posts ?? [];
$currentUser = $auth->getCurrentUser();
?>

<section class="grid grid-2">
    <div>
        <div class="card">
            <h1>Bienvenue sur <?= SITE_NAME ?></h1>
            <p><?= SITE_DESCRIPTION ?></p>
            <div class="article-actions">
                <a href="index.php?page=home" class="btn">Voir les articles</a>
                <?php if (!$currentUser): ?>
                    <a href="index.php?page=register" class="btn btn-secondary">Créer un compte</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <aside class="card">
        <h3>À propos</h3>
        <p>Un mini blog PHP simple, propre et fonctionnel pour publier des articles et discuter des idées.</p>
        <ul>
            <li>Articles publiés</li>
            <li>Commentaires</li>
            <li>Gestion d'administration</li>
        </ul>
    </aside>
</section>

<section class="post-list" style="margin-top: 32px;">
    <?php if (empty($posts)): ?>
        <div class="card empty">Aucun article pour le moment.</div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <h2>
                    <a href="index.php?page=post&id=<?= (int) $post['id']; ?>">
                        <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h2>
                <div class="meta">
                    Par <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'); ?> ·
                    <?= htmlspecialchars(Utils::formatDate($post['created_at']), ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <p>
                    <?= htmlspecialchars(
                        Utils::truncate(strip_tags($post['excerpt'] ?: $post['content']), 180),
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>
                </p>
                <div class="article-actions">
                    <a class="btn" href="index.php?page=post&id=<?= (int) $post['id']; ?>">Lire l'article</a>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
