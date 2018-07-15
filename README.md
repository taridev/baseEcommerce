baseEcommerce
=============

A Symfony project created on July 15, 2018, 12:55 am.
## Initialisation du projet :
* Modification du fichier app/config/parameters.yml
```yaml
parameters:
    database_host: 127.0.0.1 
    database_port: null
    database_name: symfonycommerce # mettez ici le nom de la base de donnée à laquelle vous accédez
    database_user: ecommerce # ici le nom d'utilisateur (souvent root)
    database_password: ecommerce # si pas de mot de passe, laissez à null
 ```
 
* démarrez votre serveur en tapant dans la console :
```
$> php app/console server:run 
[OK] Web server listening on http://127.0.0.1:8000      
```
en fonction des versions de symfony les paramètres peuvent varier. Si la commande ne fonctionne pas essayez :
_php symfony app/console server:start_

* Ouvrez votre navigateur à l'adresse indiquée : __http://127.0.0.1:8000__.

Votre site sera désormais accéssible depuis cette adresse. Pour arreter le serveur exécutez la commande
```
$> php app/console server:stop 
[OK] Stopped the web server listening on http://127.0.0.1:8000    
```
Cette manipulation n'est pas obligatoire, elle vous évite juste de taper http://localhost/blablabla/web/app_dev.php pour accéder à votre site.

## Création de notre Bundle : 
* Lancez la commande
```
$> php app/console generate:bundle
                                            
  Welcome to the Symfony bundle generator!  
```                                            

* On vous demande si vous souhaitez partager votre bundle entre plusierurs applications ; répondez [no]
```
Are you planning on sharing this bundle across multiple applications? [no]: no
``` 
* Donnez un nom à votre bundle :
```
Give your bundle a descriptive name, like BlogBundle.
Bundle name: Grocery/ShowcaseBundle
``` 
Pour a part il appartient au namespace Grocery et s'appelle ShowcaseBundle (la règle veut qu'il se termine par Bundle).

```
Bundle name [GroceryShowcaseBundle]: ShowcaseBundle
```
* Je le laisse dans le répertoire src/
```
Target Directory [src/]: 
```

* Je choisi le format de configutation yml
```
Configuration format (annotation, yml, xml, php) [annotation]: yml
```

* Il se peut qu'un message d'erreur apparaisse. Dans ce cas nous allons modifier le fichier __composer.json__ qui se trouve à la racine du projet.
```
The command was not able to configure everything automatically.  
  You'll need to make the following changes manually.  
  ```
 ### Modification du fichier composer.json
 * ouvrez le fichier
 * localisez la section
 ```json
 "autoload": {
         "psr-4": {
             "AppBundle\\": "src/AppBundle",
         },
 ```
 * nous allons supprimer la référence au bundle AppBundle qui ne nous sert à rien. Remplacez donc cette ligne par les références du bundle que l'on vient de créer
   ```json
   "autoload": {
           "psr-4": {
               "Grocery\\": "src/Grocery",
           },
   ```
   puis dans le fichier __app/AppKernel.php__ supprimez la ligne suivant : 
   ```php
            new AppBundle\AppBundle(),
   ```
   puis ouvrez le fichier __config/routing.yml__ et supprimez les lignes suivantes :
   ```yml
   app:
       resource: '@AppBundle/Controller/'
       type: annotation
   ```
   
   et enfin supprimez le dossier __src/AppBundle__
   
   Pour terminer lancez la commande suivante :
   ```
   $> composer dump-autoload
   Generating autoload files
   ```
   
Vous avez créé votre premier bundle. Nous allez maintenant créer des entités.
  
  ## Création de l'entité Article
  * Commenez par lancer l'instruction suivante dans le terminal :
  ```
$> php app/console doctrine:generate:entity
```

* Entrez le nom de l'entité : 
```
The Entity shortcut name: ShowcaseBundle:Article
```
* Remarquez qu'il faut préciser le nom du bundle auquel elle est rattachée (ici ShowcaseBundle).

```
Determine the format to use for the mapping information.

Configuration format (yml, xml, php, or annotation) [annotation]: annotation
```
* On vous demande par la suite de renseigner chaque champ de l'entité (ici ça sera link, description, price, quantity et category).
```
New field name (press <return> to stop adding fields): link
Field type [string]: 
Field length [255]: 
Is nullable [false]: 
Unique [false]: 
```
 * procédez de la même façon pour chaque attribut, changez seulement le type si nécessaire:
  pour price indiquez 
 
 ```
 Field type [string]: float
 ```
 * et pour quantity :
 ```
 Field type [string]: integer
 ```
 
 Une fois que tous les attributs ont été renseigné, appuyez sur entrer pour mettre fin à la saisie
 ```
 New field name (press <return> to stop adding fields): 
```

* Vous devriez avoir quelque chose de ce type à l'écran :
```
  created ./src/Grocery/ShowcaseBundle/Entity/
  created ./src/Grocery/ShowcaseBundle/Entity/Article.php
> Generating entity class src/Grocery/ShowcaseBundle/Entity/Article.php: OK!
> Generating repository class src/Grocery/ShowcaseBundle/Repository/ArticleRepository.php: OK!
```
Vous remarquez que 2 classes ont été créées automatiquement :
- Grocery/ShowcaseBundle/Entity/Article.php -> il s'agit de votre entité à proprement parlé
- Grocery/ShowcaseBundle/Repository/ArticleRepository.php -> il s'agit de la classe métier associée à Article. C'est dans celle-ci que vous crérez vos fonctions personnalisées d'accès à la BDD

En parlant d'accès BDD, nous allons créer notre CRUD pour l'entité Article.

## Création du CRUD d'Article
### processus de création
* lancez la commande suivante :
```
$> php app/console doctrine:generate:crud
```
* Précisez l'entité pour laquelle vous voulez créer ce CRUD, pour nous ça sera _ShowcaseBundle:Article_
```
First, give the name of the existing entity for which you want to generate a CRUD
(use the shortcut notation like AcmeBlogBundle:Post)

The Entity shortcut name: ShowcaseBundle:Article
```
* On va être amené à modifier certains articles en bdd notamment metre à jour les quantité. Symfony nous propose de générer les metodes CREATE et UPDATE
```
Do you want to generate the "write" actions [no]? yes
```
* Pour le format j'ai essayé annotation mais j'ai eu quelques soucis, __j'ai donc préféré yml__.
```
Determine the format to use for the generated CRUD.

Configuration format (yml, xml, php, or annotation) [annotation]: yml
```
* la route proposée par défaut est /article ce qui signifie qu'il créera une route pour afficher vos entité à l'adresse http://monsite/article. j'ai préféré mettre / pour avoir directement le listing de mes Articles à l'adresse http://mon_site/ 
```
Determine the routes prefix (all the routes will be "mounted" under this
prefix: /prefix/, /prefix/new, ...).

Routes prefix [/article]: /
```
* Et enfin confirmez la génération du crud en appuyant sur Entrée
```
Do you confirm generation [yes]? 
```
                   
```
Generating the CRUD code: OK
Updating the routing: Confirm automatic update of the Routing [yes]? 
Importing the CRUD routes:   updated ./src/Grocery/ShowcaseBundle/Resources/config/routing.yml
Everything is OK! Now get to work :).  
```
### Que s'est-il vraiment passé ?
Plusieurs éléments ont été généré par symfony ;
* Vous trouverez dorénavent les routes du CRUD concernant l'entité Article dans le dossier __src/Grocery/ShowcaseBundle/Resources/config/routing/artice.yml__.
                                         
 
```yaml
_index:
    path:     /
    defaults: { _controller: "ShowcaseBundle:Article:index" }
    methods:  GET

_show:
    path:     /{id}/show
    defaults: { _controller: "ShowcaseBundle:Article:show" }
    methods:  GET

_new:
    path:     /new
    defaults: { _controller: "ShowcaseBundle:Article:new" }
    methods:  [GET, POST]

_edit:
    path:     /{id}/edit
    defaults: { _controller: "ShowcaseBundle:Article:edit" }
    methods:  [GET, POST]

_delete:
    path:     /{id}/delete
    defaults: { _controller: "ShowcaseBundle:Article:delete" }
    methods:  DELETE
```
* vous trouverez le fichier __app/Resources/views/base.html.twig__ qui est le fichier twig principal de l'application dans lequel seront injectés le contenu des pages
* dans le dossier app/Resources/views/article les fichiers __edit.html.twig__, __index.html.twig__, __new.html.twig__ et __show.html.twig__ qui seront inclus dans le fichier __base.html.twig__

Si vous vous rendez maintenant sur [http://localhost:8000](http://localhost:8000) vous verrez maintenant le listing complet de vos articles présenté sour forme de tableau ! Magique.

## Filtrage par catégorie

### Création de la route : 

On veut pouvoir selectioner tous les articles d'une catégorie (par exemple fresh) et les afficher à l'adresse : [http://localhost/fresh](http://localhost/fresh)
* Dans le fichier __app/src/Grocery/ShowcaseBundle/Resources/config/routing/article.yml__ ajouter la route suivante :
```yml
_filter_category:
    path:     /{category}
    defaults: { _controller: "ShowcaseBundle:Article:filterCategory" }
    methods:  GET
```
nous venons de relier la route _filter_category avec l'url /{category} à la méthode filterCategory de notre ArticleController

### Modification du controller
Nous allons maintenant implémenter la métode filterCategory identifiée à l'étape précédente.
* ouvrez le fichier __src/Grocery/ShowcaseBundle/Controller/ArticleController.php__ et insérez le code suivant :
```php
public function filterCategoryAction($category)
    {
        // On récupère la référence vers le doctrine manager
        $em = $this->getDoctrine()->getManager();

        // On accède au Dao de 'ShowcaseBundle:Article'
        $articles = $em->getRepository('ShowcaseBundle:Article')
            ->findBy(array('category' => $category));

        // On envoie les éléments articles selectionnés à la vue
        return $this->render('article/index.html.twig', array(
            'articles' => $articles
        ));
    }
```
remarquez l'utilisation de la méthode _findBy()_ qui prend en paramètre un tableau de critères. Elle permet de filter les résultats en fonction des critères passés en paramètres.

Si nous avions voulu filter par catégorie et prix égal à 3.99 il aurait fallut passer en paramètre le tableau
```php
array('category' => $category, 'price' => 3.99);
```
Remarquez également que la vue demandée dans la fonction _render()_ est la même que celle demandée par la méthode _indexAction()_ de ArticleController. C'est normal la vue est identique, seuls les articles sont différents. On a vraiment donc une séparation entre le contenu et la vue.

### Création d'un menu pour le filtrage des catégories
On va maintenant travailler un peu avec le métier. On dispose pour cela du fichier __src/Grocery/ShowcaseBundle/Repository/ArticleRepository.php__

* Pour commencez on a besoin de rechercher en base toutes les catégories. Ajoutez le code suivant dans la classe _ArticleRepository_ : 
```php
public function getCategories()
{
    //On crée une requête du type SELECT DISTINCT category FROM article
    $categoriesObjects = $this->createQueryBuilder('a')
        ->select('a.category') // SELECT category FROM article
        ->distinct(true)  // DISTINCT
        ->getQuery()
        ->getResult();

    $categories = array();

    // On boucle sur le résultat de la requête pour en extraire un tableau de string
    // contenant l'intitulé des catégories
    foreach ($categoriesObjects as $categoryObject) {
        $categories [] = $categoryObject['category'];
    }

    return $categories;
}
```
Nous venons de créer une métode pour récupérer tous les intitulés de catégories dans un tableau. Nous allons maintenant utiliser cette méthode dans le _ArticleController_.

* Modifiez le fichier __ArticleController.php__

```php
public function indexAction()
{
    $em = $this->getDoctrine()->getManager();

    $articles = $em->getRepository('ShowcaseBundle:Article')->findAll();
    // On récupère ici les catégories
    $categories = $em->getRepository('ShowcaseBundle:Article')
        ->getCategories();

    // Et on les injectes dans les données envoyées à la vue
    return $this->render('article/index.html.twig', array(
        'articles' => $articles,
        'categories' => $categories
    ));
}
```
* On va modifier la vue maintenant. Ouvrez donc le fichier __app/Resources/views/article/index.html.twig__ et ajoutez :
```twig
<ul>
    <li><a href="{{ path('_index') }}"{% if app.request.attributes.get('category') == null %} class="active"{% endif %}>tous les articles</a></li>
    {% for category in categories %}

    <li><a href="{{ path('_filter_category', {'category': category}) }}">{{ category }}</a></li>
    {% endfor %}

</ul>
```
Notez l'utilisation de la fonction path('route_name') de twig (qui peut prendre un tableau en paramètre correspondant aux paramètres de l'url indiqués dans la route cf. __article.yml__)qui permet de retourner le chemin vers la vue demandée.

### Conclusion 
vous avez maintenant un accès complet à vos données Articles et vous pouvez filter par catégorie en fournissant très peu de code.
