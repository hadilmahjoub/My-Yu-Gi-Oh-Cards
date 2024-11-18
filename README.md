# ------- Réaliser par : Hadil MAHJOUB -------

# 🎴 Système de Gestion des Cartes Yu-Gi-Oh!

## 📄 Description
Bienvenue dans le **Système de Gestion des Collections de Cartes Yu-Gi-Oh!**, une application Web conçue pour les passionnés souhaitant organiser, gérer et partager leurs collections de cartes avec simplicité et élégance.

L'objectif principal de cette application est d'offrir une plateforme intuitive où chaque utilisateur peut :
- ✨ **Créer** et **gérer** un Pack personnel regroupant l’ensemble de ses cartes.
- 🖼️ **Organiser** ses cartes dans des Showcases thématiques, publics ou privés, pour les mettre en avant.
- 🔍 **Explorer** les Showcases publics des autres utilisateurs pour s'inspirer ou découvrir de nouvelles idées.

Cette application vise à combiner fonctionnalité et plaisir, en mettant l'accent sur une gestion organisée et une présentation esthétique des collections de cartes Yu-Gi-Oh!.

---

## 📝 Nomenclature
- 🃏 **[objet] = YGOCard** : Cela fait référence aux cartes Yu-Gi-Oh!.
- 📦 **[inventaire] = Pack** : Cela désigne une collection de cartes Yu-Gi-Oh!.
- 🖼️ **[galerie] = Showcase** : Cela désigne un sous-ensemble d'une collection de cartes Yu-Gi-Oh!.

---

## 🔧 Modèle de données
- Ajout des entités **User**, **Pack**, **Showcase**, et **YGOCard**.
- Gestion des relations :
    - 🔗 **OneToOne (1-1)** : **User** <-> **Pack** 
    - 🔗 **OneToMany (1-N)** : **Pack** <-> **YGOCard** 
    - 🔗 **ManyToMany (M-N)** : **Showcase** <-> **YGOCard** 

Le projet est développé en **PHP** avec le framework **Symfony**, et utilise **Doctrine ORM** pour la gestion de la base de données, garantissant la persistance des données pour les cartes et les packs.

---

## 🛠️ Données de test et gestion des fixtures
- **UserFixtures :** Des utilisateurs de test, y compris un administrateur et plusieurs utilisateurs standards, sont automatiquement générés lors de l'exécution des fixtures.
- **AppFixtures :**
    - 📦 Des Packs et des Showcases de test sont associés aux utilisateurs générés.
    - 🃏 Chaque Pack contient un ensemble de cartes Yu-Gi-Oh! (entité YGOCard) créées à l'aide de données réalistes.
    - 🖼️ Les Showcases sont générés avec des thèmes variés, et certaines cartes des Packs y sont associées.
- **Chargement des données de test :**
    - ⚙️ Les données de test sont chargées via la commande `symfony console doctrine:fixtures:load`.
    - 🖼️ Les images des cartes Yu-Gi-Oh! de test sont automatiquement téléchargées à partir d'un client HTTP et enregistrées dans le dossier `/public/uploads/images/ygocards/`.

---

## 🚀 Fonctionnalités principales

L'application propose un système complet de gestion des collections de cartes Yu-Gi-Oh! pour les utilisateurs authentifiés. Les principales fonctionnalités incluent :

### 🃏 1 | Gestion du Pack :
- **Consultation :** Chaque utilisateur peut accéder à son propre Pack via une interface intuitive. Le Pack contient toutes les cartes Yu-Gi-Oh! associées à cet utilisateur.
- **Ajout et Édition des cartes :** Les utilisateurs peuvent ajouter de nouvelles cartes à leur Pack grâce à un formulaire interactif. Possibilité de modifier les informations des cartes existantes dans le Pack, y compris la description, l'image (par upload) et les autres attributs.
- **Suppression :** Les utilisateurs peuvent supprimer des cartes de leur Pack en toute simplicité.

### 🖼️ 2 | Gestion des Showcases :
- **Création :** Les utilisateurs peuvent organiser leurs cartes Yu-Gi-Oh! en créant plusieurs Showcases thématiques. Chaque Showcase est personnalisé en fonction des préférences de l'utilisateur.
- **Consultation :**
    - Les Showcases publics sont visibles par tous les utilisateurs connectés.
    - Les Showcases privés ne sont accessibles qu'au propriétaire.
    - L'administrateur a un accès complet à tous les Showcases, publics ou privés.
- **Ajout de cartes :** Les utilisateurs peuvent ajouter des cartes spécifiques de leur Pack à un Showcase.
- **Édition :** Les utilisateurs peuvent modifier les informations d’un Showcase, telles que sa description ou son statut (public/privé).
- **Suppression :** Les Showcases inutilisés ou obsolètes peuvent être supprimés par leur propriétaire ou par un administrateur.

### 🔗 3 | Navigation entre les entités :
- Un utilisateur peut facilement naviguer depuis son profil vers son Pack et ses Showcases grâce à des boutons clairs et bien positionnés.
- Une liste des cartes associées à chaque Pack et Showcase est disponible avec des liens pour voir les détails de chaque carte.

### 🔒 4 | Authentification et autorisations :
- L’accès à certaines fonctionnalités est conditionné par le rôle de l’utilisateur :
    - 👤 **Utilisateur normal :** Accès uniquement à son propre Pack, à ses Showcases (publics et privés) et à l'ensemble des Showcases publics des autres utilisateurs.
    - 🛡️ **Administrateur :** Accès à toutes les données (Packs, Showcases, cartes) sans restriction.
- Des boutons spécifiques sont désactivés et des messages explicatifs s'affichent si un utilisateur tente d'accéder au Pack d’un autre utilisateur.

### 🎨 5 | Interface utilisateur :
- Une interface moderne et responsive utilisant **Bootstrap**, avec des icônes et des couleurs pour une expérience utilisateur optimale.
- Les actions comme la consultation, l’édition ou la suppression sont présentées sous forme de boutons clairement identifiables.

---

## 🌟 Optimisations prévues

### 💬 1. Messages flash :
- Des messages flash seront ajoutés pour informer l'utilisateur des actions effectuées, telles que :
    - "Carte ajoutée avec succès au Pack."
    - "Showcase mis à jour avec succès."

### 📌 2. Marques pages :
- Les utilisateurs pourront ajouter des cartes ou des Showcases à un système de favoris ou de marque-pages pour un accès rapide.
- Les marques pages seront accessibles depuis un menu dédié sur la barre de navigation.

---

## 🌐 Lien vers le dépôt GitHub
Vous pouvez accéder au code source du projet ici : [GitHub Repo](https://github.com/hadilmahjoub/My-Yu-Gi-Oh-Cards)
