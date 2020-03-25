<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

/**
 * Pagination Class extrait toute notion de calcul et de recuperation de données de no controllers
 * 
 * She need after been instanciated the Entity you want to work with
 */
class Pagination {

     /**
     * Le nom de l'entité sur laquelle on veut effectuer une pagination
     *
     * @var string
     */
    private $entityClass;

    /**
     * Le nombre d'enregistrement à récupérer
     *
     * @var integer
     */
    private $limit = 10;

    /**
     * La page sur laquelle on se trouve actuellement
     *
     * @var integer
     */
    private $currentPage = 1;

     /**
     * Le manager de Doctrine qui nous permet notamment de trouver le repository dont on a besoin
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * Le moteur de template Twig qui va permettre de générer le rendu de la pagination
     *
     * @var Twig\Environment
     */
    private $twig;

    /**
     * Le nom de la route que l'on veut utiliser pour les boutons de la navigation
     *
     * @var string
     */
    private $route;

    /**
     * Le chemin vers le template qui contient la pagination
     *
     * @var string
     */
    private$templatePath;

    /**
     * Constructeur du service de pagination qui sera appelé par Symfony
     * 
     * N'oubliez pas de configurer votre fichier services.yaml afin que Symfony sache quelle valeur
     * utiliser pour le $templatePath
     *
     * @param EntityManagerInterface $manager
     * @param Environment $twig
     * @param RequestStack $request
     * @param string $templatePath
     */
    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request,
    string $templatePath)
    {    // On récupère le nom de la route à utiliser à partir des attributs de la requête actuelle
        $this->route        =   $request->getCurrentRequest()->attributes->get('_route');
        // Autres initialisations
        $this->manager      =   $manager;
        $this->twig         =   $twig;
        $this->templatePath =   $templatePath;      
    }
    
    /**
     * Permet d'afficher le rendu de la navigation au sein d'un template twig !
     * 
     * On se sert ici de notre moteur de rendu afin de compiler le template qui se trouve au chemin
     * de notre propriété $templatePath, en lui passant les variables :
     * - page  => La page actuelle sur laquelle on se trouve
     * - pages => le nombre total de pages qui existent
     * - route => le nom de la route à utiliser pour les liens de navigation
     *
     * Attention : cette fonction ne retourne rien, elle affiche directement le rendu
     * 
     * @return void
     */
    public function display()
    {
        $this->twig->display($this->templatePath, [
            'page'  =>  $this->currentPage,
            'pages' =>  $this->getPages(),
            'route' =>  $this->route,
            ]);
        }
     
     /**
     * Permet de récupérer le nombre de pages qui existent sur une entité particulière
     * 
     * Elle se sert de Doctrine pour récupérer le repository qui correspond à l'entité que l'on souhaite
     * paginer (voir la propriété $entityClass) puis elle trouve le nombre total d'enregistrements grâce
     * à la fonction findAll() du repository
     * 
     * @throws Exception si la propriété $entityClass n'est pas configurée
     * 
     * @return int
     */    
    public function getPages(): int
    {   // Si il n'y a pas d'entité configurée, on ne peut pas charger le repository, la fonction
        // ne peut donc pas continuer !
        // include test to throw exeption message 
         if(empty($this->entityClass)) 
        {
            throw new \Exception("You need to specify the Entity you want to use Pagination!
            Use setEntityClass() method /service/Pagination.php !");
        }
    
        // How many entry on the table
        $total = count($this->manager
                        ->getRepository($this->entityClass)
                        ->findAll());
        
        
        // make division; convert to next integer 3.4 => 4; and return Data
        return ceil($total / $this->limit);
        
    }
    
    /**
     * Permet de récupérer les données paginées pour une entité spécifique
     * 
     * Elle se sert de Doctrine afin de récupérer le repository pour l'entité spécifiée
     * puis grâce au repository et à sa fonction findBy() on récupère les données dans une 
     * certaine limite et en partant d'un offset
     * 
     * @throws Exception si la propriété $entityClass n'est pas définie
     *
     * @return array
     */
    public function getData()
    {   // include test to throw exeption message 
        if(empty($this->entityClass)) {
            throw new \Exception("You need to specify the Entity you want to use Pagination!
            Use setEntityClass() method /service/Pagination.php !");
        }

        // Offset calculation
        $offset = $this->currentPage * $this->limit - $this->limit;
        // Ask Repository to find elements
        // 2) Demander au repository de trouver les éléments à partir d'un offset et 
        // dans la limite d'éléments imposée (voir propriété $limit)
        return $this->manager
                        ->getRepository($this->entityClass)
                        ->findBy([], [], $this->limit, $offset);
    }
                    
    /**
     * Permet de spécifier la page que l'on souhaite afficher
     *
     * @param int $page
     * @return self
     */               
    public function setPage(int $page): self
    {
        $this->currentPage = $page;
        
        return $this;
    }
    
    /**
     * Permet de récupérer la page qui est actuellement affichée
     *
     * @return int
     */
    public function getPage(): int
    {
        return $this->currentPage;
    }
    
    /**
     * Allow to specify how many enregistrement que l'on souhaite obtenir
     *
     * @param [int] $limit
     * @return self
     */
    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        
        return $this;
    }
    
    /**
     * Permet de recuperer le nombre d'enregistrement qui seront renvoyés
     *
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
     
    /**
     * Permet de specifier l'entité sur laquelle on souhaite paginer
     * Eg: 
     * - App\Entity\Ad::class
     * - App\Entity\Comment::class
     *
     * @param string $entityClass
     * @return self
     */
    public function setEntityClass( string $entityClass): self    
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    /**
     * Permet de récupérer l'entité sur laquelle on est en train de paginer
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * Permet de choisir un template de pagination
     *
     * @param string $templatePath
     * @return self
     */
    public function setTemplatePath($templatePath): self
    {
        $this->templatePath    =  $templatePath; 
        
        return $this;
    }
    
    /**
     * Permet de récupérer le templatePath actuellement utilisé
     *
     * @return string
     */ 
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * Permet de changer la route par défaut pour les liens de la navigation
     *
     * @param string $route Le nom de la route à utiliser
     * @return self
     */
    public function setRoute($route): self
    {
        $this->route =  $route;

        return $this;
    }

    /**
     * Permet de récupérer le nom de la route qui sera utilisé sur les liens de la navigation
     *
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

}