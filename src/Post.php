<?php
/**
 * Classe Post - Gestion des articles
 */

class Post {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Créer un nouvel article
     */
    public function create($title, $content, $excerpt, $author_id, $image = null) {
        if (empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Titre et contenu requis'];
        }

        $stmt = $this->db->query(
            'INSERT INTO posts (title, content, excerpt, author_id, image, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, NOW(), NOW())'
        );

        if ($stmt->execute([$title, $content, $excerpt, $author_id, $image])) {
            return ['success' => true, 'id' => $this->db->lastInsertId(), 'message' => 'Article créé'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la création'];
    }

    /**
     * Récupérer un article par ID
     */
    public function getById($id) {
        $stmt = $this->db->query(
            'SELECT p.*, u.username, u.email FROM posts p 
             JOIN users u ON p.author_id = u.id 
             WHERE p.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Récupérer tous les articles avec pagination
     */
    public function getAll($limit = 10, $offset = 0) {
        $stmt = $this->db->query(
            'SELECT p.*, u.username, COUNT(c.id) as comment_count 
             FROM posts p 
             JOIN users u ON p.author_id = u.id 
             LEFT JOIN comments c ON p.id = c.post_id 
             GROUP BY p.id 
             ORDER BY p.created_at DESC 
             LIMIT ? OFFSET ?'
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Compter les articles
     */
    public function count() {
        $stmt = $this->db->query('SELECT COUNT(*) as total FROM posts');
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Mettre à jour un article
     */
    public function update($id, $title, $content, $excerpt, $image = null) {
        if (empty($title) || empty($content)) {
            return ['success' => false, 'message' => 'Titre et contenu requis'];
        }

        if ($image) {
            $stmt = $this->db->query(
                'UPDATE posts SET title = ?, content = ?, excerpt = ?, image = ?, updated_at = NOW() 
                 WHERE id = ?'
            );
            $success = $stmt->execute([$title, $content, $excerpt, $image, $id]);
        } else {
            $stmt = $this->db->query(
                'UPDATE posts SET title = ?, content = ?, excerpt = ?, updated_at = NOW() 
                 WHERE id = ?'
            );
            $success = $stmt->execute([$title, $content, $excerpt, $id]);
        }

        if ($success) {
            return ['success' => true, 'message' => 'Article mis à jour'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour'];
    }

    /**
     * Supprimer un article
     */
    public function delete($id) {
        // Supprimer les commentaires associés
        $stmt = $this->db->query('DELETE FROM comments WHERE post_id = ?');
        $stmt->execute([$id]);

        // Supprimer l'article
        $stmt = $this->db->query('DELETE FROM posts WHERE id = ?');

        if ($stmt->execute([$id])) {
            return ['success' => true, 'message' => 'Article supprimé'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la suppression'];
    }

    /**
     * Rechercher des articles
     */
    public function search($keyword, $limit = 10, $offset = 0) {
        $keyword = '%' . $keyword . '%';
        $stmt = $this->db->query(
            'SELECT p.*, u.username FROM posts p 
             JOIN users u ON p.author_id = u.id 
             WHERE p.title LIKE ? OR p.content LIKE ? 
             ORDER BY p.created_at DESC 
             LIMIT ? OFFSET ?'
        );
        $stmt->execute([$keyword, $keyword, $limit, $offset]);
        return $stmt->fetchAll();
    }
}
?>