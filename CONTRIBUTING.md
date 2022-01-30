# Comment contribuer

Vous aimez notre application et avez décidé de nous aider en y contribuant ? Veuillez alors prendre connaissances de ce document qui dicte quelques règles à suivre ainsi que les bonnes pratiques attendues.

## Règles générales

Vous avez décidé de contribuer à notre projet ? Tout d'abord, merci ! Ensuite, voici quelques règles à suivre :
* Toute nouvelle fonctionnalité doit faire l'objet préalable d'une demande et d'une validation par l'auteur de l'application (voir section "Rapporter un bug/une idée").
* Toute idée ou suggestion est bonne à prendre, nous vous faisons confiance pour une bonne entente et des échanges courtois sur vos issues et pull requests.

## Consulter les tickets déjà créés

Vous avez remarqué un problème dans l'application ? Avant de créer un problème, veuillez vérifier que votre problème n'a pas déjà été soumis et/ou traité. Pour cela, rendez-vous dans les [problèmes du projet](https://github.com/mdoutreluingne/todoandco/issues) et supprimez les filtres par défaut pour afficher toutes les sorties.

## Rapporter un bug/une idée

Vous avez identifié un bug ou souhaitez suggérer une idée de développement ? Très bien ! Néanmoins, il convient de respecter le processus suivant :
1. Rendez-vous sur la [page des problèmes](https://github.com/mdoutreluingne/todoandco/issues)
2. Utiliser les [labels mis à disposition](https://github.com/mdoutreluingne/todoandco/labels) sur ce projet
3. Ajoutez votre suggestion ou bug avec le(s) label(s) correspondant(s). Il est important alors d'y spécifier :
   * La fonctionnalité concernée par l'idée ou le bug.
   * Le fichier et ligne exacte d'apparition du bug.
   * Si déjà identifié, un descriptif de comment vous comptez résoudre ce bug/développer cette idée.
4. Une fois validé par un membre de la core team, suivez les étapes décrites dans la section "Développer une fonctionnalité" ci-dessous.

## Développer une fonctionnalité

Vous souhaitez apporter votre pière à l'édifice en développant une nouvelle fonctionnalité ? Votre suggestion a été approuvée ? Parfait ! Néanmoins, il convient de respecter le processus suivant :
1. Rendez-vous sur le dépôt GitHub du projet.
2. Créez et ouvrez une [Issue](https://github.com/mdoutreluingne/todoandco/issues) correspondant à votre développement. Il est important d'y spécifier :
   * Le détail de la fonctionnalité développée.
   * Un descriptif rapide des principales étapes de développement.
   * Si c'est le cas, les bibiothèques externes qui seront utilisées et/ou installées.
3. Développez votre code.
4. Pushez votre code sur la branche correspondante (jamais sur la branche master directement !)
5. Demandez ensuite une pull request, qui sera validée ou non par l'équipe.

## Comment écrire le code 

### Qualité de code

Afin de garantir et de maintenir un haut niveau de qualité de code, quelques règles sont à respecter dans votre développement :
* Le respect des principales recommandations en vigueur est obligatoire
   * Respecter les standards W3C pour le HTML/CSS.
   * Respecter les standards PSR-1, PSR-2, PSR-12 du langage PHP. Ces recommandations font partie des [normes de codage](https://www.php-fig.org/psr/) à respecter.
   * Respecter les [bonnes pratiques de Symfony 5.4](https://symfony.com/doc/5.4/best_practices.html)
* L'utilisation d'outils tels que PHP-CS-Fixer/CodeClimate/Codacy est fortement encouragé. Chaque pull request ou apport de nouveau code à l'application doit avoir fait l'objet d'analyse via un ou plusieurs de ces outils.

### Tests unitaires et fonctionnels

Afin de maintenir le projet viable, le développement de tests unitaires et fonctionnels accompagnant vos ajouts est obligatoire.
Ces tests doivent couvrir tous les chemins possibles d'exécution du code pour garantir le bon fonctionnement et le respect des spécifications.
De plus, un test doit être ajouté lorsqu'un bogue survient pour éviter qu'il ne se reproduise.
Ceux-ci devront être réalisés avec PHPUnit afin de maintenir une cohérence.

### Performance

Avant d'ajouter une nouvelle fonctionnalité ou une amélioration de l'application, vous devez créer un profil avec Blackfire. À la fin du développement, vous devez créer un nouveau profil et résoudre les problèmes si nécessaire.
