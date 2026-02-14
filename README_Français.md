# Gestionnaire de tâches — README (Français)

## Présentation du projet

Il s'agit d'une application web simple en PHP + MySQL pour gérer des tâches. Le backend léger se trouve dans le dossier `api/` et l'interface minimale utilise `script.js` et `style.css` pour interagir avec l'API. L'application prend en charge l'inscription, la connexion, la création, la modification, la suppression et la lecture des tâches.

## Fonctionnalités

- Inscription et authentification des utilisateurs
- Création, lecture, mise à jour, suppression de tâches (par utilisateur)
- Interface web simple (`index.php`, `script.js`, `style.css`)
- Endpoints REST-like dans `api/`

## Prérequis

- PHP 7.4+ (ou compatible)
- MySQL / MariaDB
- Un serveur web (Apache via XAMPP est supporté)

## Installation

1. Placez le dossier du projet dans la racine du serveur web (ex. XAMPP `htdocs/taskmanager`).
2. Créez une base de données MySQL (ex. `taskmanager`).
3. Mettez à jour les informations de connexion dans `api/config.php`.
4. Importez le schéma de la base de données ou créez les tables (exemples SQL ci-dessous).
5. Démarrez le serveur web et ouvrez le site dans votre navigateur (ex. `http://localhost/taskmanager`).

## Schéma de la base de données (exemple)

Utilisez ce SQL d'exemple pour créer les tables minimales attendues par l'application. Ajustez si nécessaire en fonction de `api/config.php`.

```
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(100) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
	id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	title VARCHAR(255) NOT NULL,
	description TEXT,
	completed TINYINT(1) DEFAULT 0,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Réinitialiser / Nettoyer la base de données (SQL rapide)

Pour supprimer toutes les tâches et tous les utilisateurs et réinitialiser les compteurs AUTO_INCREMENT, exécutez les commandes SQL suivantes (par exemple via phpMyAdmin ou un client MySQL) :

```
DELETE FROM tasks;
DELETE FROM users;
ALTER TABLE tasks AUTO_INCREMENT = 1;
ALTER TABLE users AUTO_INCREMENT = 1;
```

Important : ces commandes suppriment définitivement toutes les données. Ne les exécutez que sur un environnement de développement ou si vous souhaitez explicitement effacer la base.

## Endpoints API

Le dossier `api/` contient les endpoints backend : résumé :

- `api/register.php` — POST : `username`, `password` — créer un utilisateur
- `api/login.php` — POST : `username`, `password` — authentifier et démarrer la session
- `api/logout.php` — GET/POST — détruire la session
- `api/getTasks.php` — GET — retourne les tâches de l'utilisateur connecté
- `api/addTask.php` — POST : `title`, `description` — ajouter une tâche pour l'utilisateur connecté
- `api/updateTask.php` — POST : `id`, `title`, `description`, `completed` — mettre à jour une tâche (doit appartenir à l'utilisateur)
- `api/deleteTask.php` — POST : `id` — supprimer une tâche (doit appartenir à l'utilisateur)

Consultez les fichiers PHP dans `api/` pour connaître les noms exacts des paramètres et les réponses attendues.

## Exemples de requêtes (curl)

Inscription :

```
curl -X POST -d "username=alice&password=secret" http://localhost/taskmanager/api/register.php
```

Connexion (sauvegarder les cookies entre requêtes) :

```
curl -c cookies.txt -X POST -d "username=alice&password=secret" http://localhost/taskmanager/api/login.php
```

Récupérer les tâches (avec cookies sauvegardés) :

```
curl -b cookies.txt http://localhost/taskmanager/api/getTasks.php
```

Ajouter une tâche :

```
curl -b cookies.txt -X POST -d "title=Nouvelle tâche&description=Détails" http://localhost/taskmanager/api/addTask.php
```

Supprimer une tâche :

```
curl -b cookies.txt -X POST -d "id=42" http://localhost/taskmanager/api/deleteTask.php
```

## Configuration

- Mettez à jour `api/config.php` avec l'hôte de base de données, l'utilisateur, le mot de passe et le nom de la base.
- Si vous hébergez l'application dans un sous-dossier, vérifiez les références d'URL dans `index.php` et `script.js`.

## Notes de sécurité

- Assurez-vous que `api/register.php` hache correctement les mots de passe (recommandé : `password_hash()` et `password_verify()` en PHP).
- Protégez les endpoints `api/` avec des sessions et vérifiez que les opérations s'appliquent uniquement aux données de l'utilisateur authentifié.
- En production, utilisez HTTPS et appliquez les bonnes pratiques de sécurité pour les applications PHP.

## Dépannage

- Pages blanches ou erreurs PHP : activez `display_errors` en développement ou consultez les logs du serveur.
- Échec de connexion à la base : vérifiez les identifiants dans `api/config.php` et que le serveur MySQL est lancé.

## Plan des fichiers

- `index.php` — page front-end
- `db.php` — (si présent) aide pour la BD ; vérifiez son contenu
- `api/` — endpoints backend (`register.php`, `login.php`, `getTasks.php`, `addTask.php`, `updateTask.php`, `deleteTask.php`, `logout.php`, `config.php`)
- `script.js`, `style.css` — ressources front-end

## Licence

MIT — vous pouvez adapter et réutiliser. Ajoutez une attribution si vous redistribuez.

## Contact / Prochaines étapes

Si vous souhaitez me contacter pour plus d'informations ou tout simplement me
donner un retour, h'hésitez pas:

Email - olivierr.joshua@gmail.com

---
Dernière mise à jour : 14 février 2026
