# Guess What

Prise en main de la POO avec PHP

Niveau : Deuxième année de BTS SIO SLAM

Prérequis : bases de la programmation, PHP 7 ou supérieur installé sur votre machine de dev.

## Objectif

Ce projet a pour but de développer une application métier pour l'entreprise VETUX-LINE.

Cette application doit lui permettre de fusionner 2 fichiers csv en un fichier unique. 

## Première partie:
### Création de la Base de donnée
J'ai créé la base de données avec phpMyAdmin pour le projet. Pour cela j'ai réduit la taille des deux fichiers csv comme demandé. J'obtient donc deux tables que je vais utiliser .Si javais utiliser les fichiers complets (2000 et 3000 lignes), il suffira d’augmenter la taille maximale de fichiers sur la DDB. Mais pour rendre les tests plus simples et compréhensifs, j'utilise les petits fichiers.

### Upload de fichier:
Premièrement, pour pouvoir faire le upload de fichier, j'ai créé un formulaire qui va contenir un input de fichiers pour uploader des fichiers csv par exemple.
Se formulaire permet de selectionner deux fichiers en même temps.
* j'ai créé un entité "upload" dans lequel y a le champ "name"
```php
class Upload
{
    /* nom du fichier */
    private $names;

    public function getNames()
    {
        return $this->names;
    }

    public function setNames($names)
    {
        $this->names= $names;

        return $this;
    }

    public function addNames($name)
    {
        $this->names[] = $name;

        return $this;
    }
```
* Ensuite j'ai fait une classe de formulaire qui contient toutes les instructions nécessaires pour créer le formulaire de tâche qui sera associé à l'entité upload 
```php
 public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('names', FileType::class, [

                'multiple' => true,
                'label'=> 'Selectionner deux fichiers csv',
                'attr' => [
                    'multiple' => 'multiple'
                ]
            ])

            ->add('submit',SubmitType::class)
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Upload::class,
        ]);
    }
```
* Ensuite j'ai fait un classe controller qui permet de le restituer
```php
/**
     * @Route("/home", name="home")
     */
    public function HomeController(Request $request)
    {
        $upload =new Upload();
        $form = $this->createForm(UploadType::class, $upload);
    //traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $files = $upload->getNames(); //récupération du fichier
            $fileName0 = $files[0]->getClientOriginalName(); // définition d'un nouveau nom de fichier
            $fileName1 = $files[1]->getClientOriginalName();

            $files[0]->move($this->getParameter('upload_directory'), $fileName0); //recupération du paramètre
            $files[1]->move($this->getParameter('upload_directory'), $fileName1);
            $upload->setNames(($fileName0));
            $upload->setNames(($fileName1));



            return $this->redirectToRoute('home');//redirection sur le home
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
```
* Dans le répertoire service.yaml, j'ai défini un paramètre qui contient le nom du répertoire ou les fichiers doivent être affiché.
```php 
parameters:
    upload_directory: '%kernel.project_dir%/public/uploads'
```
###Fusion des fichiers uploader

D'abord, pour pouvoir fusionné les fichiers j'ai indiqué le chemin dans lequel le dossier se trouve à partir d'un chemin de fichier en lecture seul grace au mode "r", puis je crée et ouvre en même temps le fichier fusionné en indiquant le chemin ou il va se trouver grace au mode "x"

Si le fichier a été ouvert, il analyse la ligne qu'il lit et recherche les champs CSV, qu'il va retourner dans un tableau "$liste" les contenant.
Et enfin on format les ligne en csv et l'écrit dans un fichier.
```php 
/**
     * @Route("/fusion", name="fusion")
     */
    public function index()
    {
        //ouverture du dossier à partir d'un chemin de fichier en lecture seul grace au mode "r"
        $csv = fopen('../public/uploads/small-french-client.csv', 'r');
        $csv1 = fopen('../public/uploads/small-german-client.csv', 'r');
        NHJTRFDX
        $fusion=fopen('../public/fusion/french-german-client.csv','x' );
        $liste=array();

        // si le fichier a été ouvert, il analyse la ligne qu'il lit et
        // recherche les champs CSV, qu'il va retourner dans un tableau "$liste"
        // les contenant.
        if($csv) {
            $ligne = fgetcsv($csv, 1000, ",");
            if ($csv1) {
                $ligne1 = fgetcsv($csv1, 1000, ",");
                $ligne1 = fgetcsv($csv1, 1000, ",");
                //fusion séquentielle
                while ($ligne) {
                    $liste[] = $ligne;
                    $ligne = fgetcsv($csv, 1000, ",");
                }
                while ($ligne1) {
                    $liste[] = $ligne1;
                    $ligne1 = fgetcsv($csv1, 1000, ",");
                }
                fclose($csv); //fermeture de fichier qui est représenter par le pointeur 
                fclose($csv1);
            }
            else{
                echo"Ouverture impossible";
            }
        }
        else{
            echo"Ouverture impossible";
        }
        foreach ($liste as $fields){
            fputcsv($fusion, $fields);
        }
        fclose($fusion);
        dump($liste);
        exit;
        
```
### Droit accés
Dans la classe Utilisateur, les rôles sont un tableau stocké dans la base de données et chaque utilisateur à un role.
```php
public function getRoles(): array
    {
        $roles = $this->roles;  //on va chercher le role de l'utilsateur
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER'; //n'importe quel utilisateur connecter aura se role

        return array_unique($roles);
    }
```






