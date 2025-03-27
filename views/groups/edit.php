<div class="max-w-4xl mx-auto bg-white p-8 border-2 border-soviet-gray soviet-container">
    <h1 class="text-2xl font-bold mb-2 uppercase tracking-wider">Gestion du Groupe: <?= htmlspecialchars($group['name']) ?></h1>
    <p class="text-gray-600 mb-6"><?= htmlspecialchars($group['description'] ?? 'Aucune description') ?></p>
    
    <?php if (isset($error)): ?>
        <div class="bg-soviet-red bg-opacity-20 border-2 border-soviet-red text-soviet-darkred px-4 py-3 mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= BASE_URL ?>/groups/edit/<?= $group['id'] ?>" method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informations du Groupe
                </h2>
                <div class="space-y-4 border-2 border-soviet-gray p-4">
                    <div>
                        <label for="name" class="block text-soviet-gray font-bold mb-2 uppercase">Nom du Groupe <span class="text-soviet-red">*</span></label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($group['name']) ?>" class="w-full px-3 py-2 border-2 border-soviet-gray" required>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-soviet-gray font-bold mb-2 uppercase">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border-2 border-soviet-gray"><?= htmlspecialchars($group['description'] ?? '') ?></textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_public" name="is_public" value="1" <?= isset($group['is_public']) && $group['is_public'] ? 'checked' : '' ?> class="mr-2">
                        <label for="is_public" class="text-soviet-gray font-bold uppercase">Groupe Public</label>
                        <div class="ml-2 text-sm text-gray-500">(Les autres utilisateurs pourront rejoindre ce groupe)</div>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Membres du Groupe
                </h2>
                <div class="border-2 border-soviet-gray p-4 max-h-60 overflow-y-auto">
                    <?php if (empty($users)): ?>
                        <p class="text-gray-500">Aucun utilisateur disponible.</p>
                    <?php else: ?>
                        <?php if ($isAdmin || $isCreator): ?>
                            <div class="mb-2 flex items-center">
                                <input type="checkbox" id="select-all-users" class="mr-2">
                                <label for="select-all-users" class="font-bold">Sélectionner/Désélectionner tous</label>
                            </div>
                            <div class="border-t border-soviet-gray pt-2">
                                <?php foreach ($users as $user): ?>
                                    <?php 
                                        $isUserCreator = isset($group['created_by']) && $group['created_by'] == $user['id'];
                                        $isDisabled = $isUserCreator; // Le créateur ne peut pas être retiré
                                    ?>
                                    <div class="flex items-center mb-2 p-2 <?= in_array($user['id'], $groupUserIds) ? 'bg-soviet-lightgray bg-opacity-30' : '' ?>">
                                        <input type="checkbox" id="user_<?= $user['id'] ?>" name="users[]" value="<?= $user['id'] ?>" <?= in_array($user['id'], $groupUserIds) ? 'checked' : '' ?> <?= $isDisabled ? 'checked disabled' : '' ?> class="mr-2 user-checkbox">
                                        <label for="user_<?= $user['id'] ?>" class="flex-grow">
                                            <span class="font-bold"><?= htmlspecialchars($user['username']) ?></span>
                                            <?php if ($user['is_admin']): ?>
                                                <span class="ml-2 bg-soviet-red text-white text-xs px-1 py-0.5">Admin</span>
                                            <?php endif; ?>
                                            <?php if ($isUserCreator): ?>
                                                <span class="ml-2 bg-soviet-gray text-white text-xs px-1 py-0.5">Créateur</span>
                                            <?php endif; ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="border-t border-soviet-gray pt-2">
                                <?php foreach ($groupUserIds as $userId): ?>
                                    <?php 
                                        $user = null;
                                        foreach ($users as $u) {
                                            if ($u['id'] == $userId) {
                                                $user = $u;
                                                break;
                                            }
                                        }
                                        if (!$user) continue;
                                        
                                        $isUserCreator = isset($group['created_by']) && $group['created_by'] == $user['id'];
                                    ?>
                                    <div class="flex items-center mb-2 p-2 bg-soviet-lightgray bg-opacity-30">
                                        <span class="font-bold"><?= htmlspecialchars($user['username']) ?></span>
                                        <?php if ($user['is_admin']): ?>
                                            <span class="ml-2 bg-soviet-red text-white text-xs px-1 py-0.5">Admin</span>
                                        <?php endif; ?>
                                        <?php if ($isUserCreator): ?>
                                            <span class="ml-2 bg-soviet-gray text-white text-xs px-1 py-0.5">Créateur</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Fichiers Partagés avec le Groupe
            </h2>
            <div class="border-2 border-soviet-gray p-4">
                <?php if (empty($files)): ?>
                    <p class="text-gray-500">Vous n'avez aucun fichier à partager avec ce groupe.</p>
                <?php else: ?>
                    <div class="mb-4 bg-soviet-lightgray p-3 border-l-4 border-soviet-red">
                        <h3 class="font-bold uppercase mb-1">Guide des permissions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                            <div><strong>r--</strong>: Lecture seule (téléchargement)</div>
                            <div><strong>rw-</strong>: Lecture et modification</div>
                            <div><strong>rwx</strong>: Lecture, modification et exécution</div>
                        </div>
                    </div>
                    
                    <div class="mb-2 flex items-center">
                        <input type="checkbox" id="select-all-files" class="mr-2">
                        <label for="select-all-files" class="font-bold">Sélectionner/Désélectionner tous</label>
                    </div>
                    
                    <div class="border-t border-soviet-gray pt-2">
                        <?php foreach ($files as $index => $file): ?>
                            <?php 
                                $isShared = in_array($file['path'], $groupFilePaths);
                                // Récupérer les permissions actuelles si le fichier est partagé
                                $currentPerms = '';
                                if ($isShared) {
                                    $currentPerms = $groupModel->getGroupFilePermission($group['id'], $file['path']) ?: 'r--';
                                }
                            ?>
                            <div class="mb-4 p-3 <?= $isShared ? 'bg-soviet-lightgray bg-opacity-30' : '' ?> border border-soviet-gray">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="file_<?= $index ?>" name="files[]" value="<?= $file['path'] ?>" <?= $isShared ? 'checked' : '' ?> class="mr-2 file-checkbox">
                                    <label for="file_<?= $index ?>" class="font-bold"><?= htmlspecialchars(basename($file['path'])) ?></label>
                                </div>
                                
                                <div class="ml-6 file-permissions <?= $isShared ? '' : 'hidden' ?>">
                                    <div class="flex flex-wrap gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="file_perms[<?= $index ?>]" value="r--" <?= $currentPerms === 'r--' ? 'checked' : '' ?> class="mr-1">
                                            <span>Lecture seule</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="file_perms[<?= $index ?>]" value="rw-" <?= $currentPerms === 'rw-' ? 'checked' : '' ?> class="mr-1">
                                            <span>Lecture et modification</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="file_perms[<?= $index ?>]" value="rwx" <?= $currentPerms === 'rwx' ? 'checked' : '' ?> class="mr-1">
                                            <span>Lecture, modification et exécution</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="flex justify-between pt-4">
            <button type="submit" class="soviet-button">
                Enregistrer les Modifications
            </button>
            <a href="<?= BASE_URL ?>/groups" class="bg-soviet-gray text-white px-4 py-2 uppercase hover:bg-black">
                Retour à la liste
            </a>
        </div>
    </form>
    
    <script>
        // Script pour gérer la sélection/désélection de tous les utilisateurs
        document.getElementById('select-all-users')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox:not([disabled])');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
        
        // Script pour gérer la sélection/désélection de tous les fichiers
        document.getElementById('select-all-files')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.file-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
                // Afficher/masquer les options de permission
                const permissionsDiv = checkbox.closest('div.mb-4').querySelector('.file-permissions');
                if (permissionsDiv) {
                    permissionsDiv.classList.toggle('hidden', !this.checked);
                }
            });
        });
        
        // Script pour afficher/masquer les options de permission lorsqu'un fichier est sélectionné
        document.querySelectorAll('.file-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const permissionsDiv = this.closest('div.mb-4').querySelector('.file-permissions');
                if (permissionsDiv) {
                    permissionsDiv.classList.toggle('hidden', !this.checked);
                }
            });
        });
    </script>
</div>

