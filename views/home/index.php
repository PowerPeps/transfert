<div class="bg-soviet-lightgray py-16">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-6 uppercase tracking-wider">Système de Partage de Fichiers</h1>
        <p class="text-xl mb-8 max-w-3xl mx-auto">Une plateforme sécurisée pour partager, gérer et collaborer sur vos fichiers avec un contrôle précis des permissions.</p>
        
        <div class="flex justify-center space-x-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/dashboard" class="soviet-button text-lg px-8 py-3">Accéder au Tableau de Bord</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/login" class="soviet-button text-lg px-8 py-3">Connexion</a>
                <a href="<?= BASE_URL ?>/register" class="bg-soviet-gray text-white text-lg px-8 py-3 hover:bg-black">Inscription</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-12 text-center uppercase tracking-wider">Fonctionnalités Principales</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-soviet-lightgray bg-opacity-20 p-8 border-t-4 border-soviet-red">
                <div class="text-soviet-red mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 uppercase">Partage Sécurisé</h3>
                <p class="text-gray-700">Partagez vos fichiers en toute sécurité avec un contrôle précis des permissions. Définissez qui peut voir, modifier ou télécharger vos fichiers.</p>
            </div>
            
            <div class="bg-soviet-lightgray bg-opacity-20 p-8 border-t-4 border-soviet-red">
                <div class="text-soviet-red mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 uppercase">Contrôle d'Expiration</h3>
                <p class="text-gray-700">Définissez des dates d'expiration ou des limites de téléchargement pour vos fichiers partagés. Gardez le contrôle sur la durée de vie de vos partages.</p>
            </div>
            
            <div class="bg-soviet-lightgray bg-opacity-20 p-8 border-t-4 border-soviet-red">
                <div class="text-soviet-red mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold mb-4 uppercase">Gestion de Groupes</h3>
                <p class="text-gray-700">Organisez les utilisateurs en groupes pour une gestion plus facile des permissions. Créez des groupes publics ou privés selon vos besoins.</p>
            </div>
        </div>
    </div>
</div>

<div class="py-16 bg-soviet-lightgray bg-opacity-30">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-12 text-center uppercase tracking-wider">Comment ça marche</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-soviet-red rounded-full h-16 w-16 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">1</div>
                <h3 class="text-xl font-bold mb-2 uppercase">Inscription</h3>
                <p class="text-gray-700">Créez votre compte en quelques secondes pour commencer à utiliser le système.</p>
            </div>
            
            <div class="text-center">
                <div class="bg-soviet-red rounded-full h-16 w-16 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">2</div>
                <h3 class="text-xl font-bold mb-2 uppercase">Téléchargement</h3>
                <p class="text-gray-700">Téléchargez vos fichiers et définissez les permissions initiales.</p>
            </div>
            
            <div class="text-center">
                <div class="bg-soviet-red rounded-full h-16 w-16 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">3</div>
                <h3 class="text-xl font-bold mb-2 uppercase">Partage</h3>
                <p class="text-gray-700">Partagez vos fichiers avec des utilisateurs individuels ou des groupes.</p>
            </div>
            
            <div class="text-center">
                <div class="bg-soviet-red rounded-full h-16 w-16 flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">4</div>
                <h3 class="text-xl font-bold mb-2 uppercase">Collaboration</h3>
                <p class="text-gray-700">Collaborez efficacement avec un contrôle précis des accès.</p>
            </div>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-8 uppercase tracking-wider">Prêt à commencer?</h2>
        <p class="text-xl mb-8 max-w-3xl mx-auto">Rejoignez notre plateforme dès aujourd'hui et découvrez une nouvelle façon de partager vos fichiers en toute sécurité.</p>
        
        <div class="flex justify-center space-x-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= BASE_URL ?>/dashboard" class="soviet-button text-lg px-8 py-3">Accéder au Tableau de Bord</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/register" class="soviet-button text-lg px-8 py-3">Créer un compte</a>
                <a href="<?= BASE_URL ?>/login" class="bg-soviet-gray text-white text-lg px-8 py-3 hover:bg-black">Se connecter</a>
            <?php endif; ?>
        </div>
    </div>
</div>

