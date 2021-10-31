<?php

namespace App\Controller;

use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class FusionController extends AbstractController
{
    /**
     * @Route("/fusion", name="fusion")
     */
    public function index()
    {
        //ouverture du dossier à partir d'un chemin de fichier en lecture seul grace au mode "r"
        $csv = fopen('../public/uploads/small-french-client.csv', 'r');
        $csv1 = fopen('../public/uploads/small-german-client.csv', 'r');
        //création et ouverture du fichier grace au mode "x"
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




        /*
         $colonne= ["Gender","Title","NameSet","GivenName","EmailAddress","Birthday","TelephoneNumber","CCType","CCNumber","CVV2","CCExpires","Vehicle","UPS", "StreetAdress","FeetInches","Kilograms"];
        */

    }
}
