<?php

namespace App\Controller;

use App\Entity\Astreinte;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $entityManager = $this->doctrine->getManager();
        $csv = '../files/astreintes2.csv';
        $file = fopen($csv, 'r');
        while (!feof($file)) {
            $line[] = fgetcsv($file, 1024);
        }
        fclose($file);
        
        for ($i=0; $i < count($line); $i++) { 
            if ($line[$i] != false) {
                $strcsv[] = str_getcsv($line[$i][0], ';');
            }
        }
        dd($strcsv);
        for ($i=0; $i < count($strcsv); $i++) { 
            if ($strcsv[$i][0] != "" && $strcsv[$i][1] != "" && $strcsv[$i][3] != "") {
                $astreinte = new Astreinte();
                $astreinte->setService(utf8_encode($strcsv[$i][0]));
                $astreinte->setTitre(utf8_encode($strcsv[$i][1]));
                $astreinte->setNom(utf8_encode($strcsv[$i][2]));
                if (utf8_encode($strcsv[$i][3] == 'sp')) {
                    $astreinte->setSurPlace('sp');
                } else {
                    $astreinte->setSurPlace(' ');
                }
                $astreinte->setCommentaires(utf8_encode($strcsv[$i][4]));
                $entityManager->persist($astreinte);
            }
        }
        $entityManager->flush();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/check_form', name: 'check_form')]
    public function checkForm(Request $request): Response
    {
        $csv = '../files/astreintes.csv';
        $file = fopen($csv, 'r');
        while (!feof($file)) {
            $line[] = fgetcsv($file, 1024);
        }
        fclose($file);
        dd($line);

        return $this->render('home/check_form.html.twig', [
            'coucou' => "Coucou !",
        ]);
    }
}
