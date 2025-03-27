<div class="max-w-4xl mx-auto bg-white p-8 border-2 border-soviet-gray soviet-container">
    <h1 class="text-2xl font-bold mb-2 uppercase tracking-wider">Gestion de l'Utilisateur: <?= htmlspecialchars($user['username']) ?></h1>
    <p class="text-gray-600 mb-6">Modifiez les informations et les appartenances aux groupes de cet utilisateur.</p>
    
    <?php if (isset($error)): ?>
        <div class="bg-soviet-red bg-opacity-20 border-2 border-soviet-red text-soviet-darkred px-4 py-3 mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= BASE_URL ?>/users/edit/<?= $user['id'] ?>" method="post" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Informations de l'Utilisateur
                </h2>
                <div class="space-y-4 border-2 border-soviet-gray p-4">
                    <div>
                        <label for="username" class="block text-soviet-gray font-bold mb-2 uppercase">Nom d'utilisateur <span class="text-soviet-red">*</span></label>
                        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full px-3 py-2 border-2 border-soviet-gray" required>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-soviet-gray font-bold mb-2 uppercase">Mot de passe</label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 border-2 border-soviet-gray">
                        <p class="text-sm text-gray-500 mt-1">Laissez vide pour conserver le mot de passe actuel</p>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" id="is_admin" name="is_admin" value="1" <?= $user['is_admin'] ? 'checked' : '' ?> class="mr-2">
                        <label for="is_admin" class="text-soviet-gray font-bold uppercase">Administrateur</label>
                        <div class="ml-2 text-sm text-gray-500">(Accès complet à la gestion du système)</div>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-xl font-bold mb-4 uppercase flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Appartenance aux Groupes
                </h2>
                <div class="border-2 border-soviet-gray p-4 max-h-60 overflow-y-auto">
                    <?php if (empty($groups)): ?>
                        <p class="text-gray-500">Aucun groupe disponible.</p>
                        <div class="mt-4">
                            <a href="<?= BASE_URL ?>/groups/create" class="soviet-button text-sm">Créer un groupe</a>
                        </div>
                    <?php else: ?>
                        <div class="mb-2 flex items-center">
                            <input type="checkbox" id="select-all-groups" class="mr-2">
                            <label for="select-all-groups" class="font-bold">Sélectionner/Désélectionner tous</label>
                        </div>
                        <div class="border-t border-soviet-gray pt-2">
                            <?php foreach ($groups as $group): ?>
                                <div class="flex items-center mb-2 p-2 <?= in_array($group['id'], $userGroupIds) ? 'bg-soviet-lightgray bg-opacity-30' : '' ?>">
                                    <input type="checkbox" id="group_<?= $group['id'] ?>" name="groups[]" value="<?= $group['id'] ?>" <?= in_array($group['id'], $userGroupIds) ? 'checked' : '' ?> class="mr-2 group-checkbox">
                                    <label for="group_<?= $group['id'] ?>" class="flex-grow">
                                        <span class="font-bold"><?= htmlspecialchars($group['name']) ?></span>
                                        <?php if (!empty($group['description'])): ?>
                                            <span class="block text-sm text-gray-500"><?= htmlspecialchars($group['description']) ?></span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="bg-soviet-lightgray p-4 border-l-4 border-soviet-red mt-4">
            <h3 class="font-bold uppercase mb-2">Informations sur les permissions</h3>
            <p>Les permissions de fichiers pour cet utilisateur sont gérées de deux façons:</p>
            <ul class="list-disc ml-5 mt-2">
                <li><strong>Permissions directes</strong>: Définies lors du téléchargement ou de la modification d'un fichier</li>
                <li><strong>Permissions de groupe</strong>: Héritées des groupes auxquels l'utilisateur appartient</li>
            </ul>
            <p class="mt-2">Pour gérer les permissions de fichiers spécifiques, utilisez la section "Mes Fichiers".</p>
        </div>
        
        <div class="flex justify-between pt-4">
            <button type="submit" class="soviet-button">
                Enregistrer les Modifications
            </button>
            <a href="<?= BASE_URL ?>/users" class="bg-soviet-gray text-white px-4 py-2 uppercase hover:bg-black">
                Retour à la liste
            </a>
        </div>
    </form>
    
    <script>
        // Script pour gérer la sélection/désélection de tous les groupes
        document.getElementById('select-all-groups')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.group-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    </script>
</div>

