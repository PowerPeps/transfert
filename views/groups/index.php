<div class="mb-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold uppercase tracking-wider">Gestion des Groupes</h1>
        <a href="<?= BASE_URL ?>/groups/create" class="soviet-button">Créer un Groupe</a>
    </div>
    <p class="mt-2 text-gray-600">Les groupes permettent de partager des fichiers et de collaborer avec d'autres utilisateurs.</p>
</div>

<!-- Mes groupes -->
<div class="bg-white p-6 border-2 border-soviet-gray soviet-container mb-8">
    <h2 class="text-2xl font-bold mb-4 uppercase">Mes Groupes</h2>
    
    <?php if (empty($groups)): ?>
        <div class="text-center py-8">
            <p class="text-gray-500 mb-4">Vous n'êtes membre d'aucun groupe.</p>
            <div class="flex justify-center gap-4">
                <a href="<?= BASE_URL ?>/groups/create" class="soviet-button">Créer un groupe</a>
                <?php if (!empty($availableGroups)): ?>
                    <a href="#available-groups" class="bg-soviet-gray text-white px-4 py-2 uppercase hover:bg-black">Rejoindre un groupe</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($groups as $group): ?>
                <?php 
                    $stats = $groupModel->getGroupStats($group['id']);
                    $isCreator = isset($group['created_by']) && $group['created_by'] == $_SESSION['user_id'];
                ?>
                <div class="border-2 border-soviet-gray p-4 relative <?= $isCreator ? 'bg-soviet-lightgray bg-opacity-20' : '' ?>">
                    <?php if ($isCreator): ?>
                        <div class="absolute top-0 right-0 bg-soviet-red text-white text-xs px-2 py-1">Créateur</div>
                    <?php endif; ?>
                    
                    <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($group['name']) ?></h3>
                    <p class="text-sm text-gray-600 mb-4"><?= htmlspecialchars($group['description'] ?? 'Aucune description') ?></p>
                    
                    <div class="flex justify-between text-sm mb-4">
                        <div>
                            <span class="font-bold"><?= $stats['member_count'] ?></span> membre(s)
                        </div>
                        <div>
                            <span class="font-bold"><?= $stats['file_count'] ?></span> fichier(s)
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-4">
                        <a href="<?= BASE_URL ?>/groups/edit/<?= $group['id'] ?>" class="soviet-button text-sm py-1 px-3">Gérer</a>
                        <?php if (!$isCreator && !$isAdmin): ?>
                            <form action="<?= BASE_URL ?>/groups/leave/<?= $group['id'] ?>" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir quitter ce groupe?');">
                                <button type="submit" class="bg-soviet-gray text-white text-sm py-1 px-3 hover:bg-black">Quitter</button>
                            </form>
                        <?php elseif ($isCreator || $isAdmin): ?>
                            <form action="<?= BASE_URL ?>/groups/delete/<?= $group['id'] ?>" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce groupe? Cette action est irréversible.');">
                                <button type="submit" class="bg-soviet-red text-white text-sm py-1 px-3 hover:bg-soviet-darkred">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Groupes disponibles (pour les utilisateurs non-admin) -->
<?php if (!$isAdmin && !empty($availableGroups)): ?>
    <div id="available-groups" class="bg-white p-6 border-2 border-soviet-gray soviet-container mb-8">
        <h2 class="text-2xl font-bold mb-4 uppercase">Groupes Disponibles</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($availableGroups as $group): ?>
                <?php $stats = $groupModel->getGroupStats($group['id']); ?>
                <div class="border-2 border-soviet-gray p-4">
                    <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($group['name']) ?></h3>
                    <p class="text-sm text-gray-600 mb-4"><?= htmlspecialchars($group['description'] ?? 'Aucune description') ?></p>
                    
                    <div class="flex justify-between text-sm mb-4">
                        <div>
                            <span class="font-bold"><?= $stats['member_count'] ?></span> membre(s)
                        </div>
                        <div>
                            <span class="font-bold"><?= $stats['file_count'] ?></span> fichier(s)
                        </div>
                    </div>
                    
                    <form action="<?= BASE_URL ?>/groups/join/<?= $group['id'] ?>" method="post">
                        <button type="submit" class="soviet-button w-full">Rejoindre ce groupe</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Guide d'utilisation -->
<div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
    <h2 class="text-xl font-bold mb-4 uppercase">Guide d'utilisation des groupes</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="border-l-4 border-soviet-red p-4 bg-soviet-lightgray bg-opacity-30">
            <h3 class="font-bold uppercase mb-2">Création</h3>
            <p>Créez des groupes pour partager des fichiers avec d'autres utilisateurs. Vous pouvez rendre un groupe public pour permettre à d'autres de le rejoindre.</p>
        </div>
        <div class="border-l-4 border-soviet-red p-4 bg-soviet-lightgray bg-opacity-30">
            <h3 class="font-bold uppercase mb-2">Partage</h3>
            <p>Partagez vos fichiers avec un groupe pour donner accès à tous ses membres. Définissez les permissions pour contrôler ce que les membres peuvent faire.</p>
        </div>
        <div class="border-l-4 border-soviet-red p-4 bg-soviet-lightgray bg-opacity-30">
            <h3 class="font-bold uppercase mb-2">Collaboration</h3>
            <p>Collaborez avec les membres de votre groupe en partageant des fichiers et en définissant des permissions appropriées.</p>
        </div>
    </div>
</div>

