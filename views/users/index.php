<div class="mb-8">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold uppercase tracking-wider">Gestion des Utilisateurs</h1>
        <a href="<?= BASE_URL ?>/users/create" class="soviet-button">Créer un Utilisateur</a>
    </div>
    <p class="mt-2 text-gray-600">Gérez les comptes utilisateurs et leurs appartenances aux groupes.</p>
</div>

<?php if (empty($users)): ?>
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container text-center">
        <p class="text-gray-500 mb-4">Aucun utilisateur n'a été créé.</p>
        <a href="<?= BASE_URL ?>/users/create" class="soviet-button">Créer votre premier utilisateur</a>
    </div>
<?php else: ?>
    <div class="bg-white p-6 border-2 border-soviet-gray soviet-container">
        <div class="overflow-x-auto">
            <table class="min-w-full border-2 border-soviet-gray">
                <thead>
                    <tr class="bg-soviet-lightgray">
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Nom d'utilisateur</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Rôle</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Groupes</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Fichiers</th>
                        <th class="py-2 px-4 text-left border-b-2 border-soviet-gray uppercase text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php 
                            $groups = $userModel->getUserGroups($user['id']);
                            $groupNames = array_map(function($group) {
                                return $group['name'];
                            }, $groups);
                            
                            // Compter les fichiers de l'utilisateur
                            $stmt = Database::getInstance()->prepare("
                                SELECT COUNT(*) as file_count 
                                FROM users_files_perm 
                                WHERE id_user = :userId
                            ");
                            $stmt->bindParam(':userId', $user['id']);
                            $stmt->execute();
                            $fileCount = $stmt->fetch(PDO::FETCH_ASSOC)['file_count'] ?? 0;
                        ?>
                        <tr class="border-b border-soviet-gray">
                            <td class="py-2 px-4 font-bold"><?= htmlspecialchars($user['username']) ?></td>
                            <td class="py-2 px-4">
                                <?php if ($user['is_admin']): ?>
                                    <span class="bg-soviet-red text-white px-2 py-1">Administrateur</span>
                                <?php else: ?>
                                    <span class="bg-soviet-lightgray px-2 py-1">Utilisateur</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-2 px-4">
                                <?php if (empty($groupNames)): ?>
                                    <span class="text-gray-500">Aucun groupe</span>
                                <?php else: ?>
                                    <div class="flex flex-wrap gap-1">
                                        <?php foreach ($groupNames as $groupName): ?>
                                            <span class="bg-soviet-lightgray px-2 py-0.5 text-sm"><?= htmlspecialchars($groupName) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-2 px-4">
                                <span class="bg-soviet-lightgray px-2 py-1 rounded"><?= $fileCount ?> fichier(s)</span>
                            </td>
                            <td class="py-2 px-4">
                                <a href="<?= BASE_URL ?>/users/edit/<?= $user['id'] ?>" class="soviet-button inline-block mr-2 text-sm py-1">Gérer</a>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <form action="<?= BASE_URL ?>/users/delete/<?= $user['id'] ?>" method="post" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur? Cette action supprimera toutes ses permissions.');">
                                        <button type="submit" class="bg-soviet-red text-white px-2 py-1 text-sm hover:bg-soviet-darkred">Supprimer</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-8 bg-white p-6 border-2 border-soviet-gray soviet-container">
        <h2 class="text-xl font-bold mb-4 uppercase">Guide de gestion des utilisateurs</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="border-l-4 border-soviet-red p-4 bg-soviet-lightgray bg-opacity-30">
                <h3 class="font-bold uppercase mb-2">Rôles</h3>
                <p><strong>Administrateur</strong>: Accès complet à la gestion des utilisateurs et des groupes.</p>
                <p><strong>Utilisateur</strong>: Accès limité à ses propres fichiers et aux fichiers partagés.</p>
            </div>
            <div class="border-l-4 border-soviet-red p-4 bg-soviet-lightgray bg-opacity-30">
                <h3 class="font-bold uppercase mb-2">Groupes</h3>
                <p>Ajoutez des utilisateurs à des groupes pour leur donner accès aux fichiers partagés avec ces groupes.</p>
            </div>
            <div class="border-l-4 border-soviet-red p-4 bg-soviet-lightgray bg-opacity-30">
                <h3 class="font-bold uppercase mb-2">Permissions</h3>
                <p>Les permissions peuvent être définies au niveau individuel ou au niveau du groupe.</p>
            </div>
        </div>
    </div>
<?php endif; ?>

