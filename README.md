# transfert

sql :
```SQL
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `mail` varchar(255),
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB;
```
.htaccess :
```htaccess
RewriteEngine On
RewriteBase /

# Protection des fichiers et dossiers sensibles
RedirectMatch 403 ^/(config|core|models|controllers|views)/
<FilesMatch "^(\.htaccess|config\.php|database\.php)$">
    Require all denied
</FilesMatch>

# Réécriture des URLs
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]

# Sécurité des en-têtes HTTP
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set X-Frame-Options "SAMEORIGIN"
</IfModule>

# Désactiver l'indexation des répertoires
Options -Indexes

# Compression des fichiers texte
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css text/javascript application/javascript application/json
</IfModule>

# Mise en cache des fichiers statiques
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresDefault "access plus 2 days"
    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```
