## 🗒️ Barème :

Maxime BONNET (SUJET 1)

| **Barème**                           | **Points** |                                                                                                                                ** Explication de la note **                                                                                                                                 |
| :-----------------------------------: |:----------:|:-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------:|
| Entités                               |    2/2     |                                                                                                                                                                                                                                                                                             |
| Fixtures                              |    2/2     |                                                                                                                                                                                                                                                                                             |
| Système de traduction                 |   0.25/1   |                                                                            Tu as un début mais il n'y a aucun bouton pour changer de langue et la route `/change-language/en` ne change pas le site en anglais.                                                                             |
| Formulaires                           |    3/4     |                           Le formulaire des produits ne fait pas assez de vérification par exemple si je rentre des lettres dans le champ stock j'ai une erreur. Il faut penser à mettre un type sur tous les champs pour stock par exemple `IntegerType::class`                            |
| Système de connexion                  |    3/3     |                                                                                                                                                                                                                                                                                             |
| Tableau de bord                       |    1/3     | Il manque les différents indicateurs comme le nombre total de produits par catégorie, ratio de disponibilité (tu as bien en stock, en rupture mais tu n'as pas un ratio par exemple 10 en stock sur 50 produits en %) et tu n'as pas le total des ventes par mois sur les 12 derniers mois. |
| Code convention (points bonus)        |   0.25/1   |                                          Imports non utilisés dans ImageType.php ligne 6 et 7. Idem dans ProductController.php ligne 10 et variable non utilisée $categoryId. Problème d'indentation dans le fichier CartController.php ligne 49.                                           |

## Sujet 1

| **Barème**                                                               | **Points** |                                                              **Description**                                                               |
| :-----------------------------------:                                    |:----------:|:------------------------------------------------------------------------------------------------------------------------------------------:|
| Création d'une page de recherche dynamique avec symfony UX               |  2.5/2.5   |                                                                                                                                            |
| Utilisation de Stimulus pour un panier dynamique et formualire dynamique |   0/2.5    | Je ne vois pas de panier dynamique, il y a toujours un refresh quand on ajoute un article au panier. Rien pour gérer les cartes bancaires. |


### Total : **14/20**