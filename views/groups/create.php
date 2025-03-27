<div class="max-w-2xl mx-auto bg-white p-8 border-2 border-soviet-gray soviet-container">
    <h1 class="text-2xl font-bold mb-2 uppercase tracking-wider">Créer un Nouveau Groupe</h1>
    <p class="text-gray-600 mb-6">Créez un groupe pour partager des fichiers et collaborer avec d'autres utilisateurs.</p>
    
    <?php if (isset($error)): ?>
        <div class="bg-soviet-red bg-opacity-20 border-2 border-soviet-red text-soviet-darkred px-4 py-3 mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= BASE_URL ?>/groups/create" method="post" class="space-y-4">
        <div>
            <label for="name" class="block text-soviet-gray font-bold mb-2 uppercase">Nom du Groupe <span class="text-soviet-red">*</span></label>
            <input type="text" id="name" name="name" class="w-full px-3 py-2 border-2 border-soviet-gray" required>
            <p class="text-sm text-gray-500 mt-1">Exemple: "Équipe Marketing", "Projet X", "Amis"</p>
        </div>
        
        <div>
            <label for="description" class="block text-soviet-gray font-bold mb-2 uppercase">Description</label>
            <textarea id="description" name="description" rows="3" class="w-full px-3 py-2 border-2 border-soviet-gray"></textarea>
            <p class="text-sm text-gray-500 mt-1">Décrivez le but de ce groupe</p>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" id="is_public" name="is_public" value="1" class="mr-2">
            <label for="is_public" class="text-soviet-gray font-bold uppercase">Groupe Public</label>
            <div class="ml-2 text-sm text-gray-500">(Les autres utilisateurs pourront rejoindre ce groupe)</div>
        </div>
        
        <div class="bg-soviet-lightgray p-4 border-l-4 border-soviet-red mt-4">
            <h3 class="font-bold uppercase mb-2">Informations importantes</h3>
            <ul class="list-disc ml-5">
                <li>En tant que créateur du groupe, vous ne pourrez pas le quitter (seulement le supprimer).</li>
                <li>Vous pourrez partager vos fichiers avec ce groupe après sa création.</li>
                <li>Si vous rendez le groupe public, d'autres utilisateurs pourront le rejoindre librement.</li>
            </ul>
        </div>
        
        <div class="flex justify-between pt-4">
            <button type="submit" class="soviet-button">
                Créer le Groupe
            </button>
            <a href="<?= BASE_URL ?>/groups" class="bg-soviet-gray text-white px-4 py-2 uppercase hover:bg-black">
                Annuler
            </a>
        </div>
    </form>
</div>

