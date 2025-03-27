<div class="mb-8">
    <h1 class="text-3xl font-bold mb-2 uppercase tracking-wider">Tableau de Bord</h1>
    <p class="text-gray-600">Bienvenue, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <?php
        // Récupérer les statistiques
        $db = Database::getInstance();
        
        // Nombre total de fichiers de l'utilisateur
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM users_files_perm WHERE id_user = :userId");
        $stmt->bindParam(':userId', $_SESSION['user_id']);
        $stmt->execute();
        $fileCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Nombre de groupes de l'utilisateur
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM user_groups WHERE user_id = :userId");
        $stmt->bindParam(':userId', $_SESSION['user_id']);
        $stmt->execute();
        $groupCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Espace utilisé
        $stmt = $db->prepare("
            SELECT SUM(f.size) as total_size 
            FROM files f
            JOIN users_files_perm ufp ON f.path = ufp.path_file
            WHERE ufp.id_user = :userId
        ");
        $stmt->bindParam(':userId', $_SESSION['user_id']);
        $stmt->execute();
        $totalSize = $stmt->fetch(PDO::FETCH_ASSOC)['total_size'] ?: 0;
        
        // Formater la taille
        function formatSize($size) {
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $i = 0;
            while ($size >= 1024 && $i < count($units) - 1) {
                $size /= 1024;
                $i++;
            }
            return round($size, 2) . ' ' . $units[$i];
        }
        
        // Activité récente
        $stmt = $db->prepare("
            SELECT f.*, ufp.perms, 'upload' as action_type, f.created_at as action_date
            FROM files f
            JOIN users_files_perm ufp ON f.path = ufp.path_file
            WHERE ufp.id_user = :userId
            ORDER BY f.created_at DESC
            LIMIT 5
        ");
        $stmt->bindParam(':userId', $_SESSION['user_id']);
        $stmt->execute();
        $recentActivity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="flex items-center">
            <div class="bg-soviet-red bg-opacity-20 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-soviet-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500 uppercase">Mes Fichiers</div>
                <div class="text-2xl font-bold"><?= $fileCount ?></div>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="flex items-center">
            <div class="bg-soviet-red bg-opacity-20 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-soviet-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500 uppercase">Mes Groupes</div>
                <div class="text-2xl font-bold"><?= $groupCount ?></div>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="flex items-center">
            <div class="bg-soviet-red bg-opacity-20 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-soviet-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500 uppercase">Espace Utilisé</div>
                <div class="text-2xl font-bold"><?= formatSize($totalSize) ?></div>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="flex items-center">
            <div class="bg-soviet-red bg-opacity-20 p-3 rounded-full mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-soviet-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <div class="text-sm text-gray-500 uppercase">Date</div>
                <div class="text-2xl font-bold"><?= date('d/m/Y') ?></div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
    <!-- Fichiers récents -->
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold uppercase">Mes Fichiers Récents</h2>
            <a href="<?= BASE_URL ?>/files/create" class="soviet-button">Télécharger</a>
        </div>
        
        <?php if (empty($files)): ?>
            <p class="text-gray-500">Vous n'avez pas encore téléchargé de fichiers.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full border-2 border-soviet-gray">
                    <thead>
                        <tr class="bg-soviet-lightgray">
                            <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Nom du fichier</th>
                            <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Permissions</th>
                            <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($files as $index => $file): ?>
                            <?php if ($index < 5): // Limiter à 5 fichiers pour le tableau de bord ?>
                                <tr class="border-b border-soviet-gray">
                                    <td class="py-2 px-4"><?= htmlspecialchars(basename($file['path'])) ?></td>
                                    <td class="py-2 px-4">
                                        <div class="flex items-center">
                                            <span class="<?= strpos($file['perms'], 'r') !== false ? 'text-soviet-red font-bold' : 'text-gray-400' ?>">R</span>
                                            <span class="mx-1">|</span>
                                            <span class="<?= strpos($file['perms'], 'w') !== false ? 'text-soviet-red font-bold' : 'text-gray-400' ?>">W</span>
                                            <span class="mx-1">|</span>
                                            <span class="<?= strpos($file['perms'], 'x') !== false ? 'text-soviet-red font-bold' : 'text-gray-400' ?>">X</span>
                                        </div>
                                    </td>
                                    <td class="py-2 px-4">
                                        <a href="<?= BASE_URL ?>/download/<?= $file['uuid'] ?>" class="text-soviet-red hover:text-soviet-darkred mr-2">Télécharger</a>
                                        <a href="<?= BASE_URL ?>/files/edit/<?= urlencode($file['path']) ?>" class="text-soviet-gray hover:text-black">Modifier</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <a href="<?= BASE_URL ?>/files" class="text-soviet-red hover:text-soviet-darkred uppercase text-sm">Voir tous les fichiers</a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Mes groupes -->
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold uppercase">Mes Groupes</h2>
            <a href="<?= BASE_URL ?>/groups/create" class="soviet-button">Créer un groupe</a>
        </div>
        
        <?php if (empty($groups)): ?>
            <p class="text-gray-500">Vous n'êtes membre d'aucun groupe.</p>
            <div class="mt-4">
                <a href="<?= BASE_URL ?>/groups" class="text-soviet-red hover:text-soviet-darkred uppercase text-sm">Découvrir les groupes disponibles</a>
            </div>
        <?php else: ?>
            <ul class="space-y-2">
                <?php foreach ($groups as $group): ?>
                    <li class="p-3 bg-soviet-lightgray border-l-4 border-soviet-red">
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="font-bold"><?= htmlspecialchars($group['name']) ?></div>
                                <?php if (!empty($group['description'])): ?>
                                    <div class="text-gray-600 text-sm"><?= htmlspecialchars($group['description']) ?></div>
                                <?php endif; ?>
                            </div>
                            <a href="<?= BASE_URL ?>/groups/edit/<?= $group['id'] ?>" class="text-soviet-red hover:text-soviet-darkred text-sm">Gérer</a>
                        </div>
                        
                        <?php
                            // Récupérer les fichiers partagés avec ce groupe
                            $stmt = Database::getInstance()->prepare("
                                SELECT f.* 
                                FROM files f
                                JOIN group_files_perm gfp ON f.path = gfp.path_file
                                WHERE gfp.group_id = :groupId
                                LIMIT 3
                            ");
                            $stmt->bindParam(':groupId', $group['id']);
                            $stmt->execute();
                            $groupFiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            if (!empty($groupFiles)):
                        ?>
                            <div class="mt-2">
                                <div class="text-sm font-bold">Fichiers partagés:</div>
                                <ul class="text-sm">
                                    <?php foreach ($groupFiles as $file): ?>
                                        <li class="mt-1">
                                            <a href="<?= BASE_URL ?>/download/<?= $file['uuid'] ?>" class="text-soviet-red hover:text-soviet-darkred">
                                                <?= htmlspecialchars(basename($file['path'])) ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="mt-4">
                <a href="<?= BASE_URL ?>/groups" class="text-soviet-red hover:text-soviet-darkred uppercase text-sm">Gérer tous mes groupes</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Activité récente -->
<div class="bg-white p-6 border-2 border-soviet-gray soviet-container mb-8">
    <h2 class="text-2xl font-bold mb-4 uppercase">Activité Récente</h2>
    
    <?php if (empty($recentActivity)): ?>
        <p class="text-gray-500">Aucune activité récente.</p>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($recentActivity as $activity): ?>
                <div class="flex items-start border-b border-soviet-gray pb-4">
                    <div class="bg-soviet-lightgray p-2 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-soviet-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                    <div class="flex-grow">
                        <div class="font-bold"><?= htmlspecialchars(basename($activity['path'])) ?></div>
                        <div class="text-sm text-gray-500">Téléchargé le <?= date('d/m/Y à H:i', strtotime($activity['action_date'])) ?></div>
                    </div>
                    <div>
                        <a href="<?= BASE_URL ?>/download/<?= $activity['uuid'] ?>" class="text-soviet-red hover:text-soviet-darkred text-sm">Télécharger</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Partage rapide -->
<div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
    <h2 class="text-2xl font-bold mb-4 uppercase">Partage Rapide</h2>
    
    <form action="<?= BASE_URL ?>/files/create" method="post" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label for="file" class="block text-soviet-gray font-bold mb-2 uppercase">Sélectionner un fichier</label>
            <input type="file" id="file" name="file" class="w-full px-3 py-2 border-2 border-soviet-gray" required>
        </div>
        
        <div>
            <label for="permissions" class="block text-soviet-gray font-bold mb-2 uppercase">Permissions</label>
            <select id="permissions" name="permissions" class="w-full px-3 py-2 border-2 border-soviet-gray">
                <option value="rwx------">Privé (Vous uniquement)</option>
                <option value="rwxr-----">Lecture (Groupe)</option>
                <option value="rwxrwx---">Lecture/Écriture (Groupe)</option>
                <option value="rwxr--r--">Public (Lecture seule)</option>
            </select>
            <div class="mt-2 text-sm">
                <div class="font-bold">Guide des permissions:</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-1">
                    <div><strong>r</strong>: Lecture (téléchargement)</div>
                    <div><strong>w</strong>: Écriture (modification)</div>
                    <div><strong>x</strong>: Exécution (téléchargement)</div>
                    <div><strong>-</strong>: Aucune permission</div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="expiration_date" class="block text-soviet-gray font-bold mb-2 uppercase">Date d'expiration (Optionnel)</label>
                <input type="date" id="expiration_date" name="expiration_date" class="w-full px-3 py-2 border-2 border-soviet-gray">
            </div>
            
            <div>
                <label for="expiration_downloads" class="block text-soviet-gray font-bold mb-2 uppercase">Téléchargements max (Optionnel)</label>
                <input type="number" id="expiration_downloads" name="expiration_downloads" min="1" class="w-full px-3 py-2 border-2 border-soviet-gray">
            </div>
        </div>
        
        <div>
            <button type="submit" class="soviet-button">
                Télécharger & Partager
            </button>
        </div>
    </form>
</div>

