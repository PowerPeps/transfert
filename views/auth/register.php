<div class="max-w-md mx-auto bg-white p-8 border-2 border-soviet-gray soviet-container">
    <h1 class="text-2xl font-bold mb-6 text-center uppercase">Inscription</h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-soviet-red bg-opacity-20 border-2 border-soviet-red text-soviet-darkred px-4 py-3 mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= BASE_URL ?>/register" method="post">
        <div class="mb-4">
            <label for="username" class="block text-soviet-gray font-bold mb-2 uppercase">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" class="w-full px-3 py-2 border-2 border-soviet-gray focus:outline-none focus:border-soviet-red" required>
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-soviet-gray font-bold mb-2 uppercase">Mot de passe</label>
            <input type="password" id="password" name="password" class="w-full px-3 py-2 border-2 border-soviet-gray focus:outline-none focus:border-soviet-red" required>
        </div>
        
        <div class="mb-6">
            <label for="confirm_password" class="block text-soviet-gray font-bold mb-2 uppercase">Confirmer le mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border-2 border-soviet-gray focus:outline-none focus:border-soviet-red" required>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="soviet-button">
                S'inscrire
            </button>
            <a href="<?= BASE_URL ?>/login" class="text-soviet-red hover:text-soviet-darkred uppercase text-sm">
                Déjà inscrit?
            </a>
        </div>
    </form>
</div>

