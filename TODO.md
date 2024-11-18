# --------------- Réaliser par : Hadil MAHJOUB ---------------

# 🎴 Système de Gestion des collections de cartes Yu-Gi-Oh! 🎴



## ✅ Check-list d'avancement du projet

| N° étape | Tâche à faire                                                                 | État | Obligatoire/Optionnel |
| -------- | ----------------------------------------------------------------------------- | ---- | --------------------- |
|    1     | Prise de connaissance du cahier des charges                                   | ✔️   | Obligatoire           |
|    2     | Initialisation du projet Symfony                                              | ✔️   | Obligatoire           |
|    3     | Gestion du code source avec Git                                               | ✔️   | Recommandé            |
|    4     | Ajout au modèle des données des entités liées **Pack** et **YGOCard**         | ✔️   | Obligatoire           |
|   4.1    | - Entité **Pack**                                                             | ✔️   | Obligatoire           |
|   4.2    | - Entité **YGOCard**                                                          | ✔️   | Obligatoire           |
|   4.3    | - Association 1-N entre **Pack** et **YGOCard**                               | ✔️   | Obligatoire           |
|   4.4    | - Propriétés non-essentielles des **YGOCard**                                 | ✔️   | Optionnel             |
|    5     | Ajout de données de tests chargeables avec les fixtures                       | ✔️   | Obligatoire           |
|   5.1    | - Fixtures pour **Pack**                                                      | ✔️   | Obligatoire           |
|   5.2    | - Fixtures pour **YGOCard**                                                   | ✔️   | Obligatoire           |
|    6     | Création des pages du "front-office" pour les **Packs**                       | ✔️   | Obligatoire           |
|   6.1    | - Consultation de la liste de tous les Packs                                  | ✔️   | Obligatoire           |
|   6.2    | - Consultation d'un Pack spécifique                                           | ✔️   | Obligatoire           |
|    7     | Utilisation de gabarits pour les pages de consultation du front-office        | ✔️   | Obligatoire           |
|   7.1    | - Consultation d'une **YGOCard**                                              | ✔️   | Obligatoire           |
|   7.2    | - Consultation de la liste des **YGOCard** d'un Pack                          | ✔️   | Obligatoire           |
|   7.3    | - Navigation d'un Pack vers ses **YGOCard**                                   | ✔️   | Obligatoire           |
|    8     | Intégration d'une mise en forme CSS avec Bootstrap dans les gabarits Twig     | ✔️   | Obligatoire           |
|    9     | Ajout de l'entité **User** et lien 1-1 avec **Pack**                          | ✔️   | Obligatoire           |
|   10     | Intégration de menus de navigation                                            | ✔️   | Obligatoire           |
|   11     | Ajout de l'entité **Showcase** et association M-N avec **YGOCard**            | ✔️   | Obligatoire           |
|   12     | Ajout d'un contrôleur CRUD pour **Showcase**                                  | ✔️   | Obligatoire           |
|   13     | Ajout de fonctions CRUD pour **YGOCard**                                      | ✔️   | Obligatoire           |
|   14     | Consultation des **YGOCard** depuis les **Showcases** publics                 | ✔️   | Obligatoire           |
|   15     | Consultation des Packs d'un utilisateur authentifié                           | ✔️   | Obligatoire           |
|   16     | Contextualisation de la création d'une **YGOCard** dans un **Pack**           | ✔️   | Obligatoire           |
|   17     | Gestion des images pour les **YGOCard**                                       | ✔️   | Obligatoire           |
|   18     | Implémentation de l'authentification                                          | ✔️   | Obligatoire           |
|   19     | Affichage des seules **Showcases** publics                                    | ✔️   | Obligatoire           |
|   20     | Contextualisation de la création d'une **Showcase** selon l'utilisateur       | ✔️   | Optionnel             |
|   21     | Contextualisation de l'ajout d'une **YGOCard** dans une **Showcase**          | ✔️   | Optionnel             |
|   22     | Utilisation des messages flash pour les CRUDs                                 | ❌   | Optionnel             |
|   23     | Ajout d'une gestion de marque-pages dans le front-office                      | ❌   | Optionnel             |
|   24     | Protection de l'accès aux données des utilisateurs                            | ✔️   | Optionnel             |
|   25     | Contextualisation du chargement des données en fonction de l'utilisateur      | ✔️   | Optionnel             |