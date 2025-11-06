# Mini Blog Laravel

Une application de blog minimaliste construite avec Laravel, incluant l'authentification via Laravel Breeze et la gestion complète des articles (posts) avec autorisation basée sur les politiques (Policies).

## Choix technique : Laravel Breeze

**Laravel Breeze** a été choisi pour ce projet car :
- Il fournit un système d'authentification complet et moderne avec des vues Blade pré-construites
- Il utilise Tailwind CSS pour un design moderne et responsive
- Il est léger et facile à personnaliser
- Il inclut toutes les fonctionnalités nécessaires : login, register, password reset, email verification
- Il s'intègre parfaitement avec le système de middleware d'authentification de Laravel

Breeze est idéal pour ce mini projet car il offre un scaffold complet sans la complexité de Jetstream.

## Prérequis

- PHP 8.2 ou supérieur
- Composer
- Node.js et NPM
- SQLite (utilisé par défaut) ou MySQL/PostgreSQL
- Extension PHP : BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## Installation

### 1. Cloner le projet

```bash
git clone <url-du-repo>
cd mon_blog
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Installer les dépendances Node.js

```bash
npm install
```

### 4. Configuration de l'environnement

Copiez le fichier `.env.example` vers `.env` :

```bash
cp .env.example .env
```

Générez la clé d'application :

```bash
php artisan key:generate
```

Si vous utilisez SQLite (défaut), assurez-vous que le fichier `database/database.sqlite` existe :

```bash
touch database/database.sqlite
```

### 5. Exécuter les migrations et seeders

```bash
# Exécuter les migrations
php artisan migrate

# Remplir la base de données avec des données de test (5 utilisateurs et 25-50 posts)
php artisan db:seed
```

Ou en une seule commande :

```bash
php artisan migrate --seed
```

### 6. Compiler les assets frontend

Pour le développement :

```bash
npm run dev
```

Pour la production :

```bash
npm run build
```

### 7. Lancer le serveur de développement

```bash
php artisan serve
```

L'application sera accessible à l'adresse `http://localhost:8000`.

## Structure du projet

### Modèles

- **User** : Modèle utilisateur fourni par Breeze (table `users`)
- **Post** : Modèle pour les articles avec les champs :
  - `id` : Identifiant unique
  - `title` : Titre de l'article (string)
  - `content` : Contenu de l'article (text)
  - `user_id` : Clé étrangère vers l'auteur (foreign key)
  - `published_at` : Date de publication (nullable datetime)
  - `timestamps` : created_at, updated_at

### Relations

- `User::hasMany(Post)` : Un utilisateur peut avoir plusieurs articles
- `Post::belongsTo(User)` : Un article appartient à un utilisateur

### Contrôleurs

- **PostController** : Contrôleur ressource pour gérer les articles
  - `index()` : Liste paginée des articles (publique)
  - `show($post)` : Affichage d'un article (publique)
  - `create()` : Formulaire de création (protégée par auth)
  - `store(StorePostRequest)` : Création d'un article (protégée par auth)
  - `edit($post)` : Formulaire d'édition (protégée par auth + policy)
  - `update(UpdatePostRequest, $post)` : Mise à jour d'un article (protégée par auth + policy)
  - `destroy($post)` : Suppression d'un article (protégée par auth + policy)

### FormRequests (Validation)

- **StorePostRequest** : Validation pour la création
  - `title` : requis, string, max 255 caractères
  - `content` : requis, string
  - `published_at` : optionnel, date

- **UpdatePostRequest** : Validation pour la mise à jour
  - Mêmes règles que StorePostRequest

### Policies (Autorisation)

- **PostPolicy** : Définit qui peut effectuer quelles actions
  - `viewAny()` : Tout le monde peut voir la liste
  - `view()` : Tout le monde peut voir un article
  - `create()` : Utilisateurs authentifiés peuvent créer
  - `update()` : Seul l'auteur peut modifier
  - `delete()` : Seul l'auteur peut supprimer

### Routes

#### Routes Web (Blade)

**Routes publiques (accessibles sans authentification)**
- `GET /` : Redirige vers la liste des articles
- `GET /posts` : Liste paginée des articles
- `GET /posts/{post}` : Affichage d'un article

**Routes protégées (nécessitent l'authentification)**
- `GET /posts/create` : Formulaire de création
- `POST /posts` : Création d'un article
- `GET /posts/{post}/edit` : Formulaire d'édition (vérifie également la policy)
- `PUT /posts/{post}` : Mise à jour d'un article (vérifie également la policy)
- `DELETE /posts/{post}` : Suppression d'un article (vérifie également la policy)

Les routes sont définies avec `Route::resource()` avec middleware `auth` et `except(['index', 'show'])` pour les routes publiques.

#### Routes API (JSON avec Sanctum)

**Routes publiques (accessibles sans authentification)**
- `GET /api/posts` : Liste paginée des articles (retourne JSON)
- `GET /api/posts/{post}` : Détail d'un article (retourne JSON)

**Routes protégées (nécessitent un token Sanctum)**
- `POST /api/posts` : Création d'un article (nécessite `Authorization: Bearer {token}`)
- `PUT /api/posts/{post}` : Mise à jour d'un article (nécessite token + vérifie la policy)
- `DELETE /api/posts/{post}` : Suppression d'un article (nécessite token + vérifie la policy)

**Format des réponses API :**
```json
{
  "success": true,
  "data": { ... },
  "message": "Message optionnel"
}
```

En cas d'erreur (validation, autorisation) :
```json
{
  "success": false,
  "errors": { ... },
  "message": "Message d'erreur"
}
```

**Codes HTTP :**
- `200` : Succès (GET, PUT, DELETE)
- `201` : Créé (POST)
- `403` : Non autorisé (policy)
- `404` : Non trouvé
- `422` : Erreur de validation

#### Routes d'authentification (Breeze)

- `GET /login` : Formulaire de connexion
- `POST /login` : Traitement de la connexion
- `GET /register` : Formulaire d'inscription
- `POST /register` : Traitement de l'inscription
- `POST /logout` : Déconnexion
- `GET /forgot-password` : Formulaire de réinitialisation de mot de passe
- `POST /forgot-password` : Envoi du lien de réinitialisation
- `GET /reset-password/{token}` : Formulaire de nouveau mot de passe
- `POST /reset-password` : Traitement du nouveau mot de passe

### Vues Blade

- `resources/views/posts/index.blade.php` : Liste paginée des articles
- `resources/views/posts/show.blade.php` : Détail d'un article
- `resources/views/posts/create.blade.php` : Formulaire de création
- `resources/views/posts/edit.blade.php` : Formulaire d'édition

Toutes les vues utilisent le layout Breeze (`resources/views/layouts/app.blade.php`) qui inclut la navigation avec Tailwind CSS.

## Commandes utiles

### Migrations

```bash
# Exécuter les migrations
php artisan migrate

# Rollback de la dernière migration
php artisan migrate:rollback

# Réinitialiser toutes les migrations
php artisan migrate:fresh

# Réinitialiser et remplir avec des données de test
php artisan migrate:fresh --seed
```

### Serveur de développement

```bash
# Lancer le serveur Laravel
php artisan serve

# Lancer le serveur sur un port spécifique
php artisan serve --port=8080
```

### Assets frontend

```bash
# Compiler les assets en mode développement (avec hot reload)
npm run dev

# Compiler les assets pour la production
npm run build

# Surveiller les changements et recompiler automatiquement
npm run watch
```

### Cache et optimisation

```bash
# Vider le cache de configuration
php artisan config:clear

# Vider le cache des routes
php artisan route:clear

# Vider le cache des vues
php artisan view:clear

# Vider tous les caches
php artisan optimize:clear

# Optimiser l'application pour la production
php artisan optimize
```

### Base de données

```bash
# Exécuter les migrations
php artisan migrate

# Exécuter les seeders (remplir la base avec des données de test)
php artisan db:seed

# Exécuter les migrations et seeders en une commande
php artisan migrate --seed

# Réinitialiser la base et exécuter les seeders
php artisan migrate:fresh --seed

# Créer un seeder
php artisan make:seeder PostSeeder

# Créer une factory
php artisan make:factory PostFactory

# Ouvrir Tinker (REPL Laravel)
php artisan tinker
```

### Seeders et données de test

Le projet inclut des seeders pour générer des données de test :

- **DatabaseSeeder** : Crée 5 utilisateurs et appelle le PostSeeder
- **PostSeeder** : Crée entre 5 et 10 posts aléatoires pour chaque utilisateur
- **PostFactory** : Génère des posts avec :
  - Titres aléatoires (3-8 mots)
  - Contenu avec plusieurs paragraphes (3-8 paragraphes)
  - 70% de posts publiés avec dates aléatoires (sur la dernière année)
  - 30% de brouillons (published_at = null)

**Résultat attendu après seeding :**
- 5 utilisateurs
- Entre 25 et 50 posts au total
- Environ 70% des posts publiés, 30% en brouillon

### Sanctum (API Authentication)

```bash
# Publier la configuration Sanctum (déjà fait)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# Exécuter les migrations Sanctum (déjà fait)
php artisan migrate
```

**Pour obtenir un token API :**

1. Créez un compte via `/register` ou connectez-vous via `/login`
2. Utilisez Tinker pour créer un token :
```bash
php artisan tinker
$user = User::find(1);
$token = $user->createToken('api-token')->plainTextToken;
echo $token;
```

3. Utilisez le token dans vos requêtes API :
```bash
curl -H "Authorization: Bearer {votre-token}" http://localhost:8000/api/posts
```

## Tests de l'authentification

### Créer un compte de test

1. Visitez `http://localhost:8000/register`
2. Remplissez le formulaire avec :
   - Nom
   - Email
   - Mot de passe (minimum 8 caractères)
   - Confirmation du mot de passe
3. Cliquez sur "Register"

### Se connecter

1. Visitez `http://localhost:8000/login`
2. Entrez vos identifiants
3. Cliquez sur "Log in"

### Tester les fonctionnalités protégées

Une fois connecté :
- Accédez à `/posts/create` pour créer un article
- Accédez à `/posts/{id}/edit` pour modifier un article (si vous en êtes l'auteur)
- Essayez d'accéder à l'édition d'un article créé par un autre utilisateur → vous recevrez une erreur 403 (Forbidden)

### Déconnexion

Cliquez sur votre nom dans le menu déroulant en haut à droite, puis sur "Log Out".

## Fonctionnalités implémentées

✅ Authentification complète avec Laravel Breeze (Blade)  
✅ Modèle Post avec relations User  
✅ Migration pour la table posts  
✅ Contrôleur PostController (resource) pour les routes web  
✅ Contrôleur API PostController pour les routes API  
✅ FormRequests pour la validation (StorePostRequest, UpdatePostRequest)  
✅ PostPolicy pour l'autorisation (seul l'auteur peut modifier/supprimer)  
✅ Routes web avec `Route::resource()` protégées par middleware auth (except index/show)  
✅ Routes API RESTful avec Sanctum (publiques pour index/show, protégées pour create/update/delete)  
✅ Vues Blade avec pagination (index, show, create, edit)  
✅ Gestion des erreurs de validation dans les formulaires  
✅ Messages de succès après les actions  
✅ Interface utilisateur moderne avec Tailwind CSS  
✅ Réponses API JSON uniformes avec codes HTTP appropriés  
✅ Seeders et Factories pour générer des données de test (5 utilisateurs, 25-50 posts)

## Technologies utilisées

- **Framework** : Laravel 12
- **Authentification Web** : Laravel Breeze (Blade + Tailwind CSS)
- **Authentification API** : Laravel Sanctum (tokens)
- **Base de données** : SQLite (par défaut, configurable)
- **Frontend** : Blade Templates, Tailwind CSS, Vite
- **Langage** : PHP 8.2+

## Structure des fichiers créés/modifiés

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   └── PostController.php (nouveau - pour les routes API)
│   │   └── PostController.php (pour les routes web)
│   └── Requests/
│       ├── StorePostRequest.php
│       └── UpdatePostRequest.php
├── Models/
│   ├── Post.php (nouveau)
│   └── User.php (modifié : relation hasMany)
├── Policies/
│   └── PostPolicy.php (nouveau)
└── Providers/
    └── AppServiceProvider.php (modifié : enregistrement de la policy)

database/
├── factories/
│   ├── PostFactory.php (nouveau - génération de posts aléatoires)
│   └── UserFactory.php (fourni par Breeze)
├── migrations/
│   ├── 2025_11_03_113553_create_posts_table.php (nouveau)
│   └── 2025_11_06_114646_create_personal_access_tokens_table.php (nouveau - Sanctum)
└── seeders/
    ├── DatabaseSeeder.php (modifié - crée 5 utilisateurs et appelle PostSeeder)
    └── PostSeeder.php (nouveau - crée 5-10 posts par utilisateur)

resources/
└── views/
    └── posts/
        ├── index.blade.php (nouveau)
        ├── show.blade.php (nouveau)
        ├── create.blade.php (nouveau)
        └── edit.blade.php (nouveau)

routes/
├── api.php (nouveau - routes API avec Sanctum)
└── web.php (modifié : routes posts avec Route::resource())

README.md (mis à jour)
```

## Notes importantes

1. **Pagination** : La liste des articles est paginée avec 10 articles par page.
2. **Published_at** : Si `published_at` est null, l'article est considéré comme un brouillon.
3. **Autorisation** : L'autorisation est vérifiée à la fois via le middleware `auth` et via les méthodes `authorize()` dans le contrôleur qui utilisent la PostPolicy.
4. **Validation** : Toute la validation se fait dans les FormRequests, pas dans le contrôleur (séparation des responsabilités).
5. **Sécurité** : Les routes mutatives (create, store, update, destroy) sont protégées par le middleware `auth`. La Policy garantit que seul l'auteur peut modifier/supprimer ses articles.
6. **Seeders** : Utilisez `php artisan migrate:fresh --seed` pour réinitialiser la base et la remplir avec des données de test (5 utilisateurs et 25-50 posts aléatoires).
7. **Modèles** : Le projet utilise uniquement les modèles `User` et `Post`. Les anciens modèles `Category` et `Article` ont été supprimés car non utilisés.

## Licence

Le framework Laravel est open-source sous la licence [MIT](https://opensource.org/licenses/MIT).

## Auteur

Projet développé dans le cadre d'un exercice Laravel - Next-U.
