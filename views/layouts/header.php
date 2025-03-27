<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Partage de Fichiers</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        soviet: {
                            red: '#c5221f',
                            darkred: '#8b0000',
                            gray: '#333333',
                            lightgray: '#e0e0e0',
                        }
                    },
                    fontFamily: {
                        sans: ['Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .soviet-button {
            background-color: #c5221f;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.2s;
        }
        .soviet-button:hover {
            background-color: #8b0000;
        }
        .soviet-container {
            border: 1px solid #333;
            box-shadow: none;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-soviet-red text-white shadow-none border-b-2 border-black">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?= BASE_URL ?>/" class="text-xl font-bold uppercase tracking-wider"><span>&#9773;</span> Système de Partage</a>
            <div class="flex items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/dashboard" class="hover:underline uppercase text-sm">Tableau de bord</a>
                    <a href="<?= BASE_URL ?>/files" class="hover:underline uppercase text-sm">Mes Fichiers</a>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <a href="<?= BASE_URL ?>/users" class="hover:underline uppercase text-sm">Utilisateurs</a>
                        <a href="<?= BASE_URL ?>/groups" class="hover:underline uppercase text-sm">Groupes</a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/logout" class="bg-soviet-darkred hover:bg-black px-3 py-1 uppercase text-sm">Déconnexion</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login" class="hover:underline uppercase text-sm">Connexion</a>
                    <a href="<?= BASE_URL ?>/register" class="bg-soviet-darkred hover:bg-black px-3 py-1 uppercase text-sm">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-6">

