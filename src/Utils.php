<?php
/**
 * Classe Utils - Fonctions utilitaires
 */

class Utils {
    /**
     * Sanitize une chaîne de caractères
     */
    public static function sanitize($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valider un email
     */
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Générer un slug à partir d'un titre
     */
    public static function generateSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }

    /**
     * Tronquer un texte
     */
    public static function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Formater une date
     */
    public static function formatDate($date, $format = 'd/m/Y H:i') {
        return date($format, strtotime($date));
    }

    /**
     * Obtenir le temps écoulé
     */
    public static function timeAgo($date) {
        $timestamp = strtotime($date);
        $difference = time() - $timestamp;

        if ($difference < 60) {
            return $difference . ' secondes';
        } elseif ($difference < 3600) {
            return floor($difference / 60) . ' minutes';
        } elseif ($difference < 86400) {
            return floor($difference / 3600) . ' heures';
        } elseif ($difference < 604800) {
            return floor($difference / 86400) . ' jours';
        } else {
            return self::formatDate($date);
        }
    }

    /**
     * Valider un fichier uploadé
     */
    public static function validateUpload($file) {
        if (!isset($file['name']) || empty($file['name'])) {
            return ['success' => false, 'message' => 'Aucun fichier sélectionné'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXTENSIONS)) {
            return ['success' => false, 'message' => 'Type de fichier non autorisé'];
        }

        if ($file['size'] > MAX_UPLOAD_SIZE) {
            return ['success' => false, 'message' => 'Le fichier est trop volumineux'];
        }

        return ['success' => true];
    }

    /**
     * Uploader un fichier
     */
    public static function uploadFile($file, $destination) {
        $validation = self::validateUpload($file);
        if (!$validation['success']) {
            return $validation;
        }

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $filepath = $destination . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => true, 'filename' => $filename, 'path' => $filepath];
        }

        return ['success' => false, 'message' => 'Erreur lors de l\'upload'];
    }

    /**
     * Générer un token CSRF
     */
    public static function generateToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Vérifier un token CSRF
     */
    public static function verifyToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>