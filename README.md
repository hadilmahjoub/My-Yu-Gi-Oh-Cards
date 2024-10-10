# --------------- Réaliser par : Hadil MAHJOUB ---------------

# 🎴 Système de Gestion des collections de cartes Yu-Gi-Oh! 🎴

## 📄 Description
Ce projet est un **Système de Gestion d'Inventaire de cartes Yu-Gi-Oh!**, conçu pour gérer et organiser des cartes Yu-Gi-Oh! en différents packs élémentaires. Il permet de gérer ces cartes organisées en différentes collections appelées **Packs**, représentant ces sept éléments principaux :
- **DARK**
- **DIVINE**
- **EARTH**
- **FIRE**
- **LIGHT**
- **WATER**
- **WIND**

### 📝 Nomenclature
- **[objet] = YGOCard** : Cela fait référence aux cartes Yu-Gi-Oh!.
- **[inventaire] = Pack** : Cela désigne une collection de cartes Yu-Gi-Oh!.

L’application repose sur une relation **one-to-many** entre les packs et les cartes, où :
- 📦 Chaque pack peut contenir plusieurs cartes Yu-Gi-Oh!.
- 🎴 Chaque carte appartient à un seul pack, catégorisé par son élément.

Le projet est développé en **PHP** avec le framework **Symfony**, et utilise **Doctrine ORM** pour la gestion de la base de données, garantissant la persistance des données pour les cartes et les packs.

## 🚀 Fonctionnalités
- **Création de Packs** : Créez dynamiquement des packs élémentaires.
- **Assignation de Cartes** : Associe automatiquement les cartes Yu-Gi-Oh! à leurs packs respectifs selon leur élément.
- **Intégration de la Base de Données** : Utilisation de Doctrine ORM pour gérer et persister les données.
- **Fixtures de Données** : Préchargez des données de test (packs et cartes) dans la base de données grâce aux DataFixtures de Symfony.

## 🌐 Lien vers le dépôt GitHub
Vous pouvez accéder au code source du projet ici : [GitHub Repo](https://github.com/hadilmahjoub/My-Yu-Gi-Oh-Cards)