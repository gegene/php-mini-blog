<?php
session_start();

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/Auth.php';
require_once __DIR__ . '/../src/Post.php';
require_once __DIR__ . '/../src/Comment.php';
require_once __DIR__ . '/../src/Utils.php';

function setFlash($message, $type = 'success') {
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

function redirect($page = 'home', $params = []) {
    $query = http_build_query($params);
    $url = 'index.php?page=' . urlencode($page);

    if (!empty($query)) {
        $url .= '&' . $query;
    }

    header('Location: ' . $url);
    exit;
}

function ensureSchema($pdo) {
    $queries = [
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            excerpt TEXT,
            content LONGTEXT NOT NULL,
            author_id INT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
        )",
        "CREATE TABLE IF NOT EXISTS comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            post_id INT NOT NULL,
            author_id INT NOT NULL,
            content TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
        )"
    ];

    foreach ($queries as $sql) {
        $pdo->exec($sql);
    }

    $count = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ((int) $count === 0) {
        $stmt = $pdo->prepare(
            'INSERT INTO users (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            'admin',
            'admin@example.com',
            password_hash('admin123', PASSWORD_BCRYPT),
            'admin'
        ]);
    }
}

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$pdo = $db->getPDO();
ensureSchema($pdo);

$auth = new Auth($db);
$postModel = new Post($db);
$commentModel = new Comment($db);

$flash = $_SESSION['flash'] ?? null;
if (isset($_SESSION['flash'])) {
    unset($_SESSION['flash']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'login':
            $result = $auth->login($_POST['email'] ?? '', $_POST['password'] ?? '');
            if ($result['success']) {
                redirect('home');
            }
            setFlash($result['message'], 'danger');
            redirect('login');
            break;

        case 'register':
            $result = $auth->register(
                $_POST['username'] ?? '',
                $_POST['email'] ?? '',
                $_POST['password'] ?? '',
                $_POST['password_confirm'] ?? ''
            );
            if ($result['success']) {
                setFlash($result['message'], 'success');
                redirect('login');
            }
            setFlash($result['message'], 'danger');
            redirect('register');
            break;

        case 'logout':
            $auth->logout();
            redirect('home');
            break;

        case 'comment':
            if (!$auth->isLogged()) {
                setFlash('Vous devez être connecté pour commenter.', 'danger');
                redirect('login');
            }

            $postId = (int) ($_POST['post_id'] ?? 0);
            $result = $commentModel->create($_POST['content'] ?? '', $postId, $_SESSION['user_id']);
            if ($result['success']) {
                setFlash($result['message'], 'success');
                redirect('post', ['id' => $postId]);
            }
            setFlash($result['message'], 'danger');
            redirect('post', ['id' => $postId]);
            break;

        case 'create_post':
            if (!$auth->isAdmin()) {
                setFlash('Accès refusé.', 'danger');
                redirect('home');
            }

            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $result = $postModel->create($title, $content, $excerpt, $_SESSION['user_id']);

            if ($result['success']) {
                setFlash('Article créé avec succès.', 'success');
                redirect('admin-posts');
            }

            setFlash($result['message'], 'danger');
            redirect('admin-posts');
            break;

        case 'update_post':
            if (!$auth->isAdmin()) {
                setFlash('Accès refusé.', 'danger');
                redirect('home');
            }

            $id = (int) ($_POST['post_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $result = $postModel->update($id, $title, $content, $excerpt);

            if ($result['success']) {
                setFlash('Article mis à jour.', 'success');
                redirect('admin-posts');
            }

            setFlash($result['message'], 'danger');
            redirect('admin-edit-post', ['id' => $id]);
            break;

        case 'delete_post':
            if (!$auth->isAdmin()) {
                setFlash('Accès refusé.', 'danger');
                redirect('home');
            }

            $id = (int) ($_POST['post_id'] ?? 0);
            $result = $postModel->delete($id);
            setFlash($result['message'], $result['success'] ? 'success' : 'danger');
            redirect('admin-posts');
            break;

        case 'delete_comment':
            if (!$auth->isAdmin()) {
                setFlash('Accès refusé.', 'danger');
                redirect('home');
            }

            $id = (int) ($_POST['comment_id'] ?? 0);
            $postId = (int) ($_POST['post_id'] ?? 0);
            $result = $commentModel->delete($id);
            setFlash($result['message'], $result['success'] ? 'success' : 'danger');
            redirect('post', ['id' => $postId]);
            break;

        default:
            redirect('home');
            break;
    }
}

$page = $_GET['page'] ?? 'home';
$view = 'home';
$title = SITE_NAME;

switch ($page) {
    case 'home':
        $posts = $postModel->getAll(10, 0);
        $title = 'Accueil';
        $view = 'home';
        break;

    case 'post':
        $postId = (int) ($_GET['id'] ?? 0);
        $post = $postModel->getById($postId);
        if (!$post) {
            setFlash('Article introuvable.', 'danger');
            redirect('home');
        }
        $comments = $commentModel->getByPostId($postId, 50, 0);
        $title = $post['title'];
        $view = 'post';
        break;

    case 'login':
        if ($auth->isLogged()) {
            redirect('home');
        }
        $title = 'Connexion';
        $view = 'auth/login';
        break;

    case 'register':
        if ($auth->isLogged()) {
            redirect('home');
        }
        $title = 'Inscription';
        $view = 'auth/register';
        break;

    case 'logout':
        $auth->logout();
        redirect('home');
        break;

    case 'admin':
    case 'admin-posts':
        if (!$auth->isLogged() || !$auth->isAdmin()) {
            setFlash('Vous devez être administrateur.', 'danger');
            redirect('login');
        }
        $posts = $postModel->getAll(100, 0);
        $title = 'Gestion des articles';
        $view = 'admin/posts';
        break;

    case 'admin-edit-post':
        if (!$auth->isLogged() || !$auth->isAdmin()) {
            setFlash('Vous devez être administrateur.', 'danger');
            redirect('login');
        }
        $postId = (int) ($_GET['id'] ?? 0);
        $editPost = $postModel->getById($postId);
        if (!$editPost) {
            setFlash('Article introuvable.', 'danger');
            redirect('admin-posts');
        }
        $title = 'Modifier un article';
        $view = 'admin/edit-post';
        break;

    default:
        redirect('home');
        break;
}

include __DIR__ . '/../views/layout.php';
?>
