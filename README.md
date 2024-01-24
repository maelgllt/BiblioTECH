# BiblioTechVue

Bienvenue sur le README de BiblioTech, une application web dédiée à la gestion de la médiathèque de la ville de Saint Barthélemy d'Anjou. 

> [!NOTE]
> Cette application ne constitue qu'une simulation et ne sera pas réellement intégrée à la médiathèque de Saint Barthélemy d'Anjou. Ce projet à été réalisé par moi-même dans la cadre de ma 3ème année de formation à l'ESAIP. C'est dans le cadre de ce projet que j'ai pu acquérir des compétences spécifiques liées à Vue.js.

## Les composants

Comme dans tout projet Vue, il y a des fichiers composants. Ici, il y a 5 composants : 
### Home.vue
C'est la page d'accueil qui contient la liste des documents disponibles dans la médiathèque (livres, journaux, CD-ROM ...).  
Pour faciliter la recherche de l'utilisateur, un système de filtrage des documents est mis en place. 
- Il peut tout d'abord afficher la liste filtrée soit par titre (par ordre alphabétique), soit par côte du document. 
- Ensuite, il peut choisir d'afficher que certaines catégories de documents à l'aide de checkbox. 
- Enfin, une barre de recherche permet à l'utilisateur d'entrer le titre du document qu'il recherche.  

Ces filtres sont complémentaires : par exemple, si l'utilisateur souhaite rechercher "Avatar", il peut utiliser la barre de recherche et l'application ressortira la liste triée par titre ou par côte ainsi que les différents types de documents existant à ce nom.

### Sidebar.vue
C'est la barre latérale qui se situe sur le côté qui sert de menu pour l'application. Elle permet d'accéder aux différents composants. A la base pensé pour être compatible sur mobile, le menu peut se réduire pour n'afficher que les icônes.

### Account.vue
C'est la page de connexion / inscription. Le système d'inscription est sécurisé (format de l'email vérifié, mot de passe complexe, confirmation de mot de passe...).

### Add_doc.vue
C'est la page qui permet d'ajouter un document dans la base de donnée. L'administrateur de la médiathèque peut facilement ajouter un document (livre, journal...) dans la base de donnée via un formulaire.

### About.vue
C'est la page qui clarifie l'objectif du projet.

## Les routes

Pour gérer les redirections vers les différents composants Vue, il a fallu configurer des routes. Pour cela, j'ai utilisé VueRouter. Voir [index.js](src/router/index.js).

