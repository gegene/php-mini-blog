# Mini Blog - Projet PHP Complet

Un système de blog PHP simple et complet avec authentification utilisateur, gestion des articles, commentaires et interface d'administration.

## 🚀 Fonctionnalités

- ✅ Authentification utilisateur (inscription/connexion)
- ✅ Gestion des articles (CRUD)
- ✅ Système de commentaires
- ✅ Interface d'administration
- ✅ Recherche d'articles
- ✅ Pagination
- ✅ Design responsive
- ✅ Validation des formulaires
- ✅ Protection contre les injections SQL (Prepared Statements)

## 📋 Requirements

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur web (Apache/Nginx)

## 📁 Structure du Projet

```
php-mini-blog/
├── config/
│   ├── database.php          # Configuration DB
│   └── constants.php         # Constantes globales
├── public/
│   ├── index.php            # Point d'entrée principal
│   ├── css/
│   │   └── style.css        # Styles
│   └── uploads/             # Dossier pour les uploads
├── src/
│   ├── Database.php         # Classe de gestion DB
│   ├── Auth.php             # Classe d'authentification
│   ├── Post.php             # Classe Post
│   ├── Comment.php          # Classe Comment
│   └── Utils.php            # Fonctions utilitaires
├── views/
│   ├── layout.php           # Template principal
│   ├── home.php             # Page d'accueil
│   ├── post.php             # Vue d'un article
│   ├── admin/               # Dossier admin
│   │   ├── dashboard.php    # Tableau de bord
│   │   ├── posts.php        # Gestion des posts
│   │   ├── edit-post.php    # Édition d'article
│   │   └── comments.php     # Gestion commentaires
│   └── auth/
│       ├── login.php        # Connexion
│       └── register.php     # Inscription
└── .gitignore
```

## 🔧 Installation

### 1. Cloner le repository
```bash
git clone https://github.com/gegene/php-mini-blog.git
cd php-mini-blog
```

### 2. Configurer la base de données

Créer un fichier `config/database.php`:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'mini_blog');
define('DB_USER', 'root');
define('DB_PASS', '');
?>
```

### 3. Créer la base de données

Lancer le script SQL:
```bash
mysql -u root < database.sql
```

### 4. Démarrer le serveur
```bash
cd public
php -S localhost:8000
```

Accéder à: `http://localhost:8000`

## 👤 Compte de test

- Email: `admin@example.com`
- Mot de passe: `admin123`

## 📝 Utilisation

### Visiteur
- Consulter les articles
- Lire les commentaires
- S'inscrire pour commenter

### Utilisateur connecté
- Créer des commentaires
- Éditer ses commentaires

### Administrateur
- Gérer tous les articles (créer/éditer/supprimer)
- Modérer les commentaires
- Voir les statistiques

## 🛡️ Sécurité

- ✅ Prepared Statements pour toutes les requêtes SQL
- ✅ Hachage des mots de passe (bcrypt)
- ✅ Sessions sécurisées
- ✅ Validation et sanitization des inputs
- ✅ Protection CSRF

## 📄 License

MIT License - voir LICENSE

## 👨‍💻 Auteur

Gègene - 2024
