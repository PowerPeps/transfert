<div class="mb-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold uppercase tracking-wider">Mes Fichiers</h1>
        <a href="<?= BASE_URL ?>/files/create" class="soviet-button">Télécharger un fichier</a>
    </div>
</div>

<?php if (empty($files)): ?>
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container text-center">
        <p class="text-gray-500 mb-4">Vous n'avez pas encore téléchargé de fichiers.</p>
        <a href="<?= BASE_URL ?>/files/create" class="soviet-button">Télécharger votre premier fichier</a>
    </div>
<?php else: ?>
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="overflow-x-auto">
            <table class="min-w-full border-2 border-soviet-gray">
                <thead>
                    <tr class="bg-soviet-lightgray">
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Nom du fichier</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Permissions</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Date d'expiration</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Téléchargements restants</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file): ?>
                        <tr class="border-b border-soviet-gray">
                            <td class="py-2 px-4"><?= htmlspecialchars(basename($file['path'])) ?></td>
                            <td class="py-2 px-4"><?= htmlspecialchars($file['perms']) ?></td>
                            <td class="py-2 px-4">
                                <?= $file['expiration_date'] ? date('d/m/Y', strtotime($file['expiration_date'])) : 'Jamais' ?>
                            </td>
                            <td class="py-2 px-4">
                                <?= $file['expiration_nb_download'] !== null ? $file['expiration_nb_download'] : 'Illimité' ?>
                            </td>
                            <td class="py-2 px-4">
                                <a href="<?= BASE_URL ?>/download/<?= $file['uuid'] ?>" class="text-soviet-red hover:text-soviet-darkred mr-2" target="_blank">Télécharger</a>
                                <a href="<?= BASE_URL ?>/files/edit/<?= $file['id']; ?>" class="text-soviet-gray hover:text-black">Modifier</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="mt-8 bg-white p-6 border-2 border-soviet-gray soviet-container">
    <h2 class="text-xl font-bold mb-4 uppercase">Liens de partage</h2>
    <p class="mb-4">Partagez vos fichiers en envoyant le lien de téléchargement direct :</p>
    
    <div class="space-y-4">
        <?php foreach ($files as $index => $file): ?>
            <?php if ($index < 3): ?>
                <div class="flex items-center border-2 border-soviet-gray p-3">
                    <div class="flex-grow">
                        <div class="font-semibold"><?= htmlspecialchars(basename($file['path'])) ?></div>
                        <div class="text-sm text-gray-500 break-all"><?= BASE_URL ?>/download/<?= $file['uuid'] ?></div>
                    </div>
                    <button 
                        class="bg-soviet-lightgray hover:bg-soviet-gray hover:text-white px-3 py-1 uppercase text-sm"
                        onclick="navigator.clipboard.writeText('<?= BASE_URL ?>/download/<?= $file['uuid'] ?>').then(() => alert('Lien copié!'))">
                        Copier
                    </button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

