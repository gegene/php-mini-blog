<?php
$editPost = $editPost ?? null;
?>
<div class="card">
    <h1>Modifier un article</h1>

    <?php if (!$editPost): ?>
        <p class="empty">Article introuvable.</p>
        <a class="btn" href="index.php?page=admin-posts">Retour</a>
    <?php else: ?>
        <form method="post" action="index.php?page=admin-edit-post&id=<?= (int) $editPost['id']; ?>" class="form-grid">
            <input type="hidden" name="action" value="update_post">
            <input type="hidden" name="post_id" value="<?= (int) $editPost['id']; ?>">

            <div>
                <label for="title">Titre</label>
                <input id="title" type="text" name="title" value="<?= htmlspecialchars($editPost['title'], ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>

            <div>
                <label for="excerpt">Extrait</label>
                <textarea id="excerpt" name="excerpt"><?= htmlspecialchars($editPost['excerpt'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div>
                <label for="content">Contenu</label>
                <textarea id="content" name="content" required><?= htmlspecialchars($editPost['content'], ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="article-actions">
                <button type="submit" class="btn">Enregistrer</button>
                <a href="index.php?page=admin-posts" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>
