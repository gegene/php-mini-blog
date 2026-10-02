<?php
$post = $post ?? null;
$comments = $comments ?? [];
$currentUser = $auth->getCurrentUser();
?>

<article class="card">
    <h1><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
    <div class="meta">
        Publié par <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8'); ?> ·
        <?= htmlspecialchars(Utils::formatDate($post['created_at']), ENT_QUOTES, 'UTF-8'); ?>
    </div>

    <div class="post-content">
        <?= nl2br(htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8')); ?>
    </div>
</article>

<section class="card" style="margin-top: 30px;">
    <h2>Commentaires</h2>

    <?php if (!$currentUser): ?>
        <p>Vous devez être <a href="index.php?page=login">connecté</a> pour commenter.</p>
    <?php else: ?>
        <form method="post" action="index.php?page=post&id=<?= (int) $post['id']; ?>" class="form-grid">
            <input type="hidden" name="action" value="comment">
            <input type="hidden" name="post_id" value="<?= (int) $post['id']; ?>">

            <div>
                <label for="content">Votre commentaire</label>
                <textarea id="content" name="content" required></textarea>
            </div>

            <button class="btn" type="submit">Publier</button>
        </form>
    <?php endif; ?>

    <div class="comment-list">
        <?php if (empty($comments)): ?>
            <div class="empty">Aucun commentaire pour le moment.</div>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment-item">
                    <strong><?= htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    <div class="meta"><?= htmlspecialchars(Utils::formatDate($comment['created_at']), ENT_QUOTES, 'UTF-8'); ?></div>
                    <div><?= nl2br(htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8')); ?></div>

                    <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                        <form method="post" action="index.php?page=post&id=<?= (int) $post['id']; ?>" style="margin-top:12px;">
                            <input type="hidden" name="action" value="delete_comment">
                            <input type="hidden" name="comment_id" value="<?= (int) $comment['id']; ?>">
                            <input type="hidden" name="post_id" value="<?= (int) $post['id']; ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
