<?php
/**
 * Classe Comment - Gestion des commentaires
 */

class Comment {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Créer un commentaire
     */
    public function create($content, $post_id, $author_id) {
        if (empty($content)) {
            return ['success' => false, 'message' => 'Le commentaire ne peut pas être vide'];
        }

        if (strlen($content) > 1000) {
            return ['success' => false, 'message' => 'Le commentaire ne peut pas dépasser 1000 caractères'];
        }

        $stmt = $this->db->query(
            'INSERT INTO comments (post_id, author_id, content, created_at) 
             VALUES (?, ?, ?, NOW())'
        );

        if ($stmt->execute([$post_id, $author_id, $content])) {
            return ['success' => true, 'id' => $this->db->lastInsertId(), 'message' => 'Commentaire ajouté'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'ajout du commentaire'];
    }

    /**
     * Récupérer les commentaires d'un article
     */
    public function getByPostId($post_id, $limit = 20, $offset = 0) {
        $stmt = $this->db->query(
            'SELECT c.*, u.username FROM comments c 
             JOIN users u ON c.author_id = u.id 
             WHERE c.post_id = ? 
             ORDER BY c.created_at DESC 
             LIMIT ? OFFSET ?'
        );
        $stmt->execute([$post_id, $limit, $offset]);
        return $stmt->fetchAll();
    }

    /**
     * Compter les commentaires d'un article
     */
    public function countByPostId($post_id) {
        $stmt = $this->db->query('SELECT COUNT(*) as total FROM comments WHERE post_id = ?');
        $stmt->execute([$post_id]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    /**
     * Récupérer un commentaire
     */
    public function getById($id) {
        $stmt = $this->db->query(
            'SELECT c.*, u.username FROM comments c 
             JOIN users u ON c.author_id = u.id 
             WHERE c.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Mettre à jour un commentaire
     */
    public function update($id, $content, $author_id) {
        if (empty($content)) {
            return ['success' => false, 'message' => 'Le commentaire ne peut pas être vide'];
        }

        $stmt = $this->db->query(
            'UPDATE comments SET content = ? 
             WHERE id = ? AND author_id = ?'
        );

        if ($stmt->execute([$content, $id, $author_id])) {
            return ['success' => true, 'message' => 'Commentaire mis à jour'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la mise à jour'];
    }

    /**
     * Supprimer un commentaire
     */
    public function delete($id, $author_id = null) {
        $stmt = $this->db->query('DELETE FROM comments WHERE id = ? AND (author_id = ? OR ? IS NULL)');

        if ($stmt->execute([$id, $author_id, $author_id])) {
            return ['success' => true, 'message' => 'Commentaire supprimé'];
        }

        return ['success' => false, 'message' => 'Erreur lors de la suppression'];
    }

    /**
     * Récupérer tous les commentaires (admin)
     */
    public function getAll($limit = 20, $offset = 0) {
        $stmt = $this->db->query(
            'SELECT c.*, u.username, p.title FROM comments c 
             JOIN users u ON c.author_id = u.id 
             JOIN posts p ON c.post_id = p.id 
             ORDER BY c.created_at DESC 
             LIMIT ? OFFSET ?'
        );
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }
}
?>