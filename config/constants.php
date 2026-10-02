<?php
/**
 * Fichier de constantes globales
 */

// Chemins
define('BASE_PATH', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', BASE_PATH . '/public');
define('VIEWS_PATH', BASE_PATH . '/views');
define('SRC_PATH', BASE_PATH . '/src');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// URLs
define('BASE_URL', 'http://localhost:8000');
define('ASSETS_URL', BASE_URL . '/assets');

// Site
define('SITE_NAME', 'Mini Blog');
define('SITE_DESCRIPTION', 'Un blog PHP simple et complet');

// Pagination
define('POSTS_PER_PAGE', 10);
define('COMMENTS_PER_PAGE', 20);

// Sécurité
define('SESSION_LIFETIME', 3600); // 1 heure
define('MAX_UPLOAD_SIZE', 5242880); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'pdf']);
?>