<?php

namespace App\Controller;

use DateTime;
use App\Entity\Ads;
use App\Form\AdsType;
use App\Entity\Photos;
use App\Repository\AdsRepository;
use App\Repository\CitiesRepository;
use App\Repository\PhotosRepository;
use App\Services\SimpleUploadService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartmentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;



class AdsController extends AbstractController
{
    //Formulaire contenant tous les départments
    #[Route('/location', name: 'app_location')]
    public function index(DepartmentsRepository $departmentsRepository): Response
    {
        $departments= $departmentsRepository->findBy([], ['number' => 'ASC']);
    
        return $this->render('ads/location.html.twig', [
            'departments' => $departments
        ]);
    }
    //Récupération des données transmises par le code location.js qui transmets des données à l'url get-cities...
    #[Route('/get-cities/{departmentNumber}', name: 'get_cities')]
    public function getCities(
        string $departmentNumber,
        Request $request,
        CitiesRepository $citiesRepository,
        SerializerInterface $serializer
    ): Response {
        //extrait la valeur du parametre de requete "search"-si pas de parametre,utilise valeur nulle
        $searchQuery = $request->query->get('search', '');
        //Voir Methode dans Repository
        $cities = $citiesRepository->findBySearchQuery($departmentNumber, $searchQuery);
    
        $jsonData = $serializer->serialize($cities, 'json', [
            'groups' => ['exclude_ads'], // Ajoutez le groupe pour exclure les références circulaires
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['department_number'],
        ]);
    
        // Create a JsonResponse
        $response = new JsonResponse($jsonData, JsonResponse::HTTP_OK, [], true);
    
        return $response;
    }


    //Formulaire de création d'annonce (lié à l'entité Cities)
    #[Route('/create-ad/{cityId}', name: 'create_ad')]
    public function createAd(
        Request $request,
         $cityId,
         CitiesRepository $citiesRepository,
          EntityManagerInterface $em,
          TokenStorageInterface $tokenStorage,
          SimpleUploadService $simpleUploadService): Response
    {
        $user = $tokenStorage->getToken()->getUser();
     
        $city = $citiesRepository->find($cityId);
       
        if (!$city) {
            throw $this->createNotFoundException('City not found');
        }

        $ad = new Ads();
        $ad->setCity($city);
        $ad->setUser($user);
        $form = $this->createForm(AdsType::class, $ad, ['city' => $city]);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $request->files->all();
            $images = $photos['ads']['photos'];
            
            foreach($images as $image){
                $new_photos = new Photos();
                $image_name = $image['name'];
                $new_photo = $simpleUploadService->uploadImage($image_name);
                $new_photos->setName($new_photo);
                $ad->addPhoto($new_photos);


            }
            $ad->setCity($form->get('city')->getData());
            $em->persist($ad);
           
            $em->flush();

            // Redirigez vers une page de confirmation ou ailleurs
            return $this->redirectToRoute('app_home');
        }

        // Affichez le formulaire dans le template
        return $this->render('ads/create_ad.html.twig', [
            'form' => $form->createView(),
            'city' => $city,
        ]);
    }
    //voir Annonces
    #[Route('/consult-ads', name: 'app_consult_ads')]
    public function consultAds(
        Request $request,
        AdsRepository $adsRepository,
         DepartmentsRepository $departmentsRepository,
         PhotosRepository $photosRepository): Response
    {
        $ads = $adsRepository->findAll();
      
        $departments = $departmentsRepository->findBy([], ['number' => 'ASC']);    
        $selectedDepartment = $request->query->get('department', null);
    
        if ($selectedDepartment) {
            // Appliquez le filtre par département
            $ads = $adsRepository->findByDepartment($selectedDepartment);
           
            // Si aucun résultat n'est trouvé, redirigez vers la même page sans le paramètre de département
        
        }
        return $this->render('ads/consult_ads.html.twig', [
            'ads' => $ads,
            'departments' => $departments,
            'selectedDepartment' => $selectedDepartment,
        ]);
    }

    #[Route('/consult-ad/{id}', name: 'app_consult_ad_by_id')]
    public function consultAdById(
        int $id,
        AdsRepository $adsRepository,
        PhotosRepository $photosRepository
    ):Response
        {
        $ad = $adsRepository->findOneById($id);
        $photos= $photosRepository->findByAdId($id);
     
        return $this->render('ads/consult_ad_by_id.html.twig', [
            'ad' => $ad,
            'photos'=> $photos
        ]);
    }
}