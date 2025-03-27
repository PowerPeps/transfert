<div class="max-w-2xl mx-auto bg-white p-8 border-2 border-soviet-gray soviet-container">
    <h1 class="text-2xl font-bold mb-6 uppercase tracking-wider">Télécharger un nouveau fichier</h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-soviet-red bg-opacity-20 border-2 border-soviet-red text-soviet-darkred px-4 py-3 mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
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
        
        <div class="flex justify-between">
            <button type="submit" class="soviet-button">
                Télécharger
            </button>
            <a href="<?= BASE_URL ?>/files" class="bg-soviet-gray text-white px-4 py-2 uppercase hover:bg-black">
                Annuler
            </a>
        </div>
    </form>
</div>

