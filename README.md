# Application de Gestion de Colocation

## Description

Application web développée avec Laravel permettant de gérer efficacement une colocation :

-Gestion des membres

-Suivi des dépenses partagées

-Calcul automatique des soldes

-Vue simplifiée des remboursements

-Système de réputation

-Administration globale

L’objectif est d’automatiser les calculs financiers afin de réduire les conflits et d’assurer une transparence totale entre colocataires.

## Stack Technique

-Framework : Laravel (Architecture MVC)

-Base de données : MySQL

-ORM : Eloquent

-Authentification : Laravel Jetstream

-Frontend : Blade et Tailwind CSS

## Rôles et Permissions

### Member

-Rejoindre une colocation via invitation

-Ajouter des dépenses

-Consulter son solde

-Marquer un paiement

-Quitter une colocation

### Owner

-Créer une colocation

-Inviter des membres

-Retirer un membre

-Gérer les catégories

-Annuler la colocation

### Global Admin

-Accéder aux statistiques globales

-Bannir ou débannir des utilisateurs

-Le premier utilisateur inscrit est automatiquement promu Global Admin.

## Fonctionnalités

### Gestion des colocations

-Création d’une colocation avec attribution automatique du rôle Owner

-Invitation par token unique envoyé par email

-Restriction à une seule colocation active par utilisateur

-Départ d’un membre (avec enregistrement de la date de sortie)

-Annulation d’une colocation (changement de statut)

### Gestion des dépenses

-Ajout d’une dépense : titre, montant, date, catégorie, payeur

-Historique des dépenses

-Filtrage des dépenses par mois

-Statistiques mensuelles et par catégorie

### Balances et dettes

#### Calcul automatique

-Total payé par membre

-Part individuelle

-Solde créditeur ou débiteur

-Vue synthétique “qui doit à qui”

-Réduction des dettes via action “Marquer payé”

### Réputation

-Départ ou annulation avec dette : -1

-Départ ou annulation sans dette : +1

-Si un Owner retire un membre ayant une dette, celle-ci est imputée à l’Owner (règle métier actuelle)

### Administration

#### Tableau de bord global

-Nombre d’utilisateurs

-Nombre de colocations

-Volume des dépenses

-Liste des utilisateurs bannis

-Blocage automatique des utilisateurs bannis

-Règles Métier Importantes

-Un utilisateur ne peut avoir qu’une seule colocation active.

-L’acceptation d’une invitation est bloquée si un membership actif existe déjà.

-Vérification de la correspondance email lors de l’acceptation d’une invitation.

-Les utilisateurs bannis sont automatiquement déconnectés et empêchés d’accéder à l’application.

## Architecture

-Application monolithique basée sur l’architecture MVC de Laravel :

-Models : gestion des entités (User, Colocation, Expense, Category, Membership)

-Controllers : logique métier et gestion des flux

-Views : Blade templates

-Relations Eloquent : hasMany, belongsToMany avec pivot Membership
