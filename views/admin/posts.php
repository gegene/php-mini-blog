<?php
$posts = $posts ?? [];
?>
<div class="card">
    <h1>Gestion des articles</h1>

    <div class="article-actions">
        <a href="index.php?page=admin-posts" class="btn">Liste des articles</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="4" class="empty">Aucun article pour le moment.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($posts as $postItem): ?>
                    <tr>
                        <td><?= htmlspecialchars($postItem['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= htmlspecialchars($postItem['username'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= htmlspecialchars(Utils::formatDate($postItem['created_at']), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="table-actions">
                            <a class="btn btn-secondary" href="index.php?page=admin-edit-post&id=<?= (int) $postItem['id']; ?>">Modifier</a>
                            <form method="post" action="index.php?page=admin-posts" style="display:inline;">
                                <input type="hidden" name="action" value="delete_post">
                                <input type="hidden" name="post_id" value="<?= (int) $postItem['id']; ?>">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer cet article ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="card" style="margin-top: 24px;">
    <h2>Créer un article</h2>
    <form method="post" action="index.php?page=admin-posts" class="form-grid">
        <input type="hidden" name="action" value="create_post">

        <div>
            <label for="title">Titre</label>
            <input id="title" type="text" name="title" required>
        </div>

        <div>
            <label for="excerpt">Extrait</label>
            <textarea id="excerpt" name="excerpt"></textarea>
        </div>

        <div>
            <label for="content">Contenu</label>
            <textarea id="content" name="content" required></textarea>
        </div>

        <button type="submit" class="btn">Publier l'article</button>
    </form>
</div>
