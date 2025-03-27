<div class="max-w-4xl mx-auto bg-white p-8 border-2 border-soviet-gray soviet-container">
    <h1 class="text-2xl font-bold mb-2 uppercase tracking-wider">Modifier le fichier</h1>
    <p class="text-gray-600 mb-6">Gérez les paramètres et les permissions de partage de ce fichier.</p>
    
    <?php if (isset($error)): ?>
        <div class="bg-soviet-red bg-opacity-20 border-2 border-soviet-red text-soviet-darkred px-4 py-3 mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <div class="mb-6 bg-soviet-lightgray p-4 border-l-4 border-soviet-red">
        <h2 class="text-lg font-semibold mb-2 uppercase">Informations sur le fichier</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p><strong>Nom:</strong> <?= htmlspecialchars(basename($file['path'])) ?></p>
                <p><strong>UUID:</strong> <?= htmlspecialchars($file['uuid']) ?></p>
                <p><strong>Taille:</strong> <?= isset($file['size']) ? number_format($file['size'] / 1024, 2) . ' KB' : 'Non disponible' ?></p>
            </div>
            <div>
                <p><strong>Date de création:</strong> <?= isset($file['created_at']) ? date('d/m/Y H:i', strtotime($file['created_at'])) : 'Non disponible' ?></p>
                <p><strong>Lien de téléchargement:</strong></p>
                <div class="flex items-center mt-1">
                    <input type="text" value="<?= BASE_URL ?>/download/<?= $file['uuid'] ?>" class="w-full px-2 py-1 border-2 border-soviet-gray text-sm" id="download-link" readonly>
                    <button onclick="copyToClipboard('download-link')" class="bg-soviet-gray text-white px-2 py-1 ml-2 text-sm">Copier</button>
                </div>
            </div>
        </div>
    </div>
    
    <form action="<?= BASE_URL ?>/files/edit/<?= $file['id'] ?>" method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Permissions Personnelles
                </h2>
                <div class="space-y-4 border-2 border-soviet-gray p-4">
                    <div>
                        <label for="permissions" class="block text-soviet-gray font-bold mb-2 uppercase">Permissions</label>
                        <select id="permissions" name="permissions" class="w-full px-3 py-2 border-2 border-soviet-gray">
                            <option value="rwx------" <?= isset($file['perms']) && $file['perms'] === 'rwx------' ? 'selected' : '' ?>>Privé (Vous uniquement)</option>
                            <option value="rwxr-----" <?= isset($file['perms']) && $file['perms'] === 'rwxr-----' ? 'selected' : '' ?>>Lecture (Groupe)</option>
                            <option value="rwxrwx---" <?= isset($file['perms']) && $file['perms'] === 'rwxrwx---' ? 'selected' : '' ?>>Lecture/Écriture (Groupe)</option>
                            <option value="rwxr--r--" <?= isset($file['perms']) && $file['perms'] === 'rwxr--r--' ? 'selected' : '' ?>>Public (Lecture seule)</option>
                        </select>
                        <div class="mt-2 text-sm">
                            <div class="font-bold">Guide des permissions:</div>
                            <div class="grid grid-cols-1 gap-1 mt-1">
                                <div><strong>r</strong>: Lecture (téléchargement)</div>
                                <div><strong>w</strong>: Écriture (modification)</div>
                                <div><strong>x</strong>: Exécution (téléchargement)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Paramètres d'Expiration
                </h2>
                <div class="space-y-4 border-2 border-soviet-gray p-4">
                    <div>
                        <label for="expiration_date" class="block text-soviet-gray font-bold mb-2 uppercase">Date d'expiration (Optionnel)</label>
                        <input type="date" id="expiration_date" name="expiration_date" value="<?= $file['expiration_date'] ? date('Y-m-d', strtotime($file['expiration_date'])) : '' ?>" class="w-full px-3 py-2 border-2 border-soviet-gray">
                        <p class="text-sm text-gray-500 mt-1">Le fichier ne sera plus accessible après cette date</p>
                    </div>
                    
                    <div>
                        <label for="expiration_downloads" class="block text-soviet-gray font-bold mb-2 uppercase">Téléchargements max (Optionnel)</label>
                        <input type="number" id="expiration_downloads" name="expiration_downloads" min="1" value="<?= $file['expiration_nb_download'] ?? '' ?>" class="w-full px-3 py-2 border-2 border-soviet-gray">
                        <p class="text-sm text-gray-500 mt-1">Le fichier ne sera plus accessible après ce nombre de téléchargements</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div>
            <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Partage avec des Groupes
            </h2>
            <div class="border-2 border-soviet-gray p-4">
                <?php if (empty($groups)): ?>
                    <p class="text-gray-500">Vous n'êtes membre d'aucun groupe. Rejoignez ou créez un groupe pour partager ce fichier.</p>
                    <div class="mt-4">
                        <a href="<?= BASE_URL ?>/groups" class="soviet-button text-sm">Gérer mes groupes</a>
                    </div>
                <?php else: ?>
                    <div class="mb-4 bg-soviet-lightgray p-3 border-l-4 border-soviet-red">
                        <h3 class="font-bold uppercase mb-1">Partage avec des groupes</h3>
                        <p class="text-sm">Sélectionnez les groupes avec lesquels vous souhaitez partager ce fichier et définissez les permissions pour chaque groupe.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <?php foreach ($groups as $index => $group): ?>
                            <?php 
                                // Vérifier si le fichier est déjà partagé avec ce groupe
                                $isShared = false;
                                $currentPerms = 'r--';
                                
                                if (!empty($sharedGroups)) {
                                    foreach ($sharedGroups as $sharedGroup) {
                                        if ($sharedGroup['id'] == $group['id']) {
                                            $isShared = true;
                                            $currentPerms = $sharedGroup['perms'];
                                            break;
                                        }
                                    }
                                }
                            ?>
                            <div class="p-3 border border-soviet-gray <?= $isShared ? 'bg-soviet-lightgray bg-opacity-30' : '' ?>">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="share_group_<?= $group['id'] ?>" name="share_groups[]" value="<?= $group['id'] ?>" <?= $isShared ? 'checked' : '' ?> class="mr-2 share-group-checkbox">
                                    <label for="share_group_<?= $group['id'] ?>" class="font-bold"><?= htmlspecialchars($group['name']) ?></label>
                                </div>
                                
                                <div class="ml-6 group-permissions <?= $isShared ? '' : 'hidden' ?>">
                                    <div class="flex flex-wrap gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="group_perms[<?= $index ?>]" value="r--" <?= $currentPerms === 'r--' ? 'checked' : '' ?> class="mr-1">
                                            <span>Lecture seule</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="group_perms[<?= $index ?>]" value="rw-" <?= $currentPerms === 'rw-' ? 'checked' : '' ?> class="mr-1">
                                            <span>Lecture et modification</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="group_perms[<?= $index ?>]" value="rwx" <?= $currentPerms === 'rwx' ? 'checked' : '' ?> class="mr-1">
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
            <div>
                <button type="submit" class="soviet-button mr-2">
                    Enregistrer
                </button>
                <a href="<?= BASE_URL ?>/files" class="bg-soviet-gray text-white px-4 py-2 uppercase hover:bg-black">
                    Annuler
                </a>
            </div>
            
            <form action="<?= BASE_URL ?>/files/delete/<?= $file['id'] ?>" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fichier? Cette action est irréversible.');">
                <button type="submit" class="bg-soviet-red text-white px-4 py-2 uppercase hover:bg-soviet-darkred">
                    Supprimer
                </button>
            </form>
        </div>
    </form>
    
    <script>
        // Fonction pour copier le lien de téléchargement
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            document.execCommand('copy');
            alert('Lien copié dans le presse-papiers!');
        }
        
        // Script pour afficher/masquer les options de permission lorsqu'un groupe est sélectionné
        document.querySelectorAll('.share-group-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const permissionsDiv = this.closest('div.p-3').querySelector('.group-permissions');
                if (permissionsDiv) {
                    permissionsDiv.classList.toggle('hidden', !this.checked);
                }
            });
        });
    </script>
</div>

