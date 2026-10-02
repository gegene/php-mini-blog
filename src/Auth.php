<?php
/**
 * Classe Auth - Gestion de l'authentification
 */

class Auth {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    /**
     * Inscrire un nouvel utilisateur
     */
    public function register($username, $email, $password, $password_confirm) {
        // Validation
        if (empty($username) || empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Tous les champs sont requis'];
        }

        if ($password !== $password_confirm) {
            return ['success' => false, 'message' => 'Les mots de passe ne correspondent pas'];
        }

        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Email invalide'];
        }

        // Vérifier si l'email existe
        $stmt = $this->db->query('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Cet email est déjà utilisé'];
        }

        // Créer l'utilisateur
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->query('INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())');
        
        if ($stmt->execute([$username, $email, $hashed_password, 'user'])) {
            return ['success' => true, 'message' => 'Inscription réussie! Vous pouvez maintenant vous connecter'];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'inscription'];
    }

    /**
     * Connecter un utilisateur
     */
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            return ['success' => false, 'message' => 'Email et mot de passe requis'];
        }

        $stmt = $this->db->query('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Email ou mot de passe incorrect'];
        }

        // Créer la session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        return ['success' => true, 'message' => 'Connexion réussie'];
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout() {
        session_destroy();
        return ['success' => true, 'message' => 'Déconnexion réussie'];
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function isLogged() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    /**
     * Obtenir l'utilisateur actuel
     */
    public function getCurrentUser() {
        if (!$this->isLogged()) {
            return null;
        }
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email'],
            'role' => $_SESSION['role']
        ];
    }
}
?>