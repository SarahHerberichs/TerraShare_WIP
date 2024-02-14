<?php

namespace App\Controller;

use DateTime;
use App\Entity\Ads;
use App\Entity\User;
use App\Form\AdsType;
use App\Entity\Photos;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Lexer\Token;
use App\Repository\AdsRepository;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use App\Repository\CitiesRepository;
use App\Repository\PhotosRepository;
use App\Repository\StatusRepository;
use App\Services\SimpleUploadService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartmentsRepository;
use App\Repository\TransactionRepository;
use App\Services\ImageCompressionService;
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
        $userLoggedIn = $this->getUser() !== null;

        return $this->render('ads/location.html.twig', [
            'departments' => $departments,
            'userLoggedIn' => $userLoggedIn,
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
        //Extrait la valeur du parametre de requete "search"-si pas de parametre,utilise valeur nulle
        $searchQuery = $request->query->get('search', '');
        //Voir Methode dans Repository
        $cities = $citiesRepository->findBySearchQuery($departmentNumber, $searchQuery);
    
        $jsonData = $serializer->serialize($cities, 'json', [
            'groups' => ['exclude_ads'], // Ajoutez le groupe pour exclure les références circulaires
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['department_number'],
        ]);
    
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
          SimpleUploadService $simpleUploadService,
          ImageCompressionService $imageCompressionService
          ): Response
        
    {
        
        $user = $tokenStorage->getToken()->getUser();
        
        $city = $citiesRepository->find($cityId);
       
        if (!$city) {
            throw $this->createNotFoundException('City not found');
        }
        $errorMessage = '';
        $ad = new Ads();
        $ad->setCity($city);
        $ad->setUser($user);
        $form = $this->createForm(AdsType::class, $ad, ['city' => $city]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $photos = $request->files->all();
           
            if (!empty($photos)){
               
                $images = $photos['ads']['photos'];
                foreach($images as $image){
                    $new_photos = new Photos();
                    $image_name = $image['name'];
                    //taille image loadée
                    $imageSizeKb = (($image['name']->getSize()))/1024;
                    //Défini une taille max d'image
                    $maxImageSizeKb = 100;
                    //upload de l'image
                    $new_photo = $simpleUploadService->uploadImage($image_name);
                    //Si taille max dépassée, défini un taux de compression à passer au service compression
                    if ($imageSizeKb > $maxImageSizeKb ) {
                        //Recherche le taux de compression à appliquer, et le passe au compresseur(compresse et redimensionne)
                        $compressTaux =($maxImageSizeKb * 100)/$imageSizeKb;
               
                        $imageCompressionService->compressImage($compressTaux,$new_photo);
                        $new_photos->setName("compress_".$new_photo);
                    }
                    $ad->addPhoto($new_photos);
                     
                    $ad->setCity($form->get('city')->getData());
                    
                    $em->persist($ad);
                    $em->flush();
            
                    return $this->redirectToRoute('app_home');
                }
       
            } else{
                $errorMessage = 'Postez au moins une photo SVP';
            }
     
        }

        //Affichage du formulaire dans le template
        return $this->render('ads/create_ad.html.twig', [
            'form' => $form->createView(),
            'city' => $city,
            'errorMessage' => $errorMessage
        ]);
    }
   
    #[Route('/consult-ads', name: 'app_consult_ads')]
    public function consultAds(
        Request $request,
        AdsRepository $adsRepository,
        DepartmentsRepository $departmentsRepository,
        TypeRepository $typeRepository,
        TransactionRepository $transactionRepository,
        StatusRepository $statusRepository
    ): Response {
        $selectedDepartment = $request->query->get('department', null);
        $selectedType = $request->query->get('type', null);
        $selectedStatus = $request->query->get('status', null);
        $selectedTransaction = $request->query->get('transaction', null);
        $selectedMinPrice = $request->query->get('minPrice', null);
        $selectedMaxPrice = $request->query->get('maxPrice', null);
    
        // Récupération des annonces sans filtres pour calculer le total
        $allAds = $adsRepository->findAll();
        
        // Filtrage des annonces selon la valeur des filtres récupérés par request->query (formulaire utilisateur)
        $ads = $adsRepository->findByFilters(
            $selectedDepartment,
            $selectedType,
            $selectedStatus,
            $selectedTransaction,
            $selectedMinPrice,
            $selectedMaxPrice,
        );
        //Tri des ads en ordre décroissant
        usort($ads, function($a, $b) {
            return $b->getCreatedAt() <=> $a->getCreatedAt();
        });
    
        // Pagination
        $currentPage = $request->query->getInt('page', 1);
        $perPage = 3;
        $totalItems = count($ads);
        $totalPages = ceil($totalItems / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        $ads = array_slice($ads, $offset, $perPage);
    
        $departments = $departmentsRepository->findBy([], ['number' => 'ASC']);
        $types = $typeRepository->findAll();
        $status = $statusRepository->findAll();
        $transactions = $transactionRepository->findAll();
    
        return $this->render('ads/consult_ads.html.twig', [
            'ads' => $ads,
            'departments' => $departments,
            'types' => $types,
            'status' => $status,
            'transactions' => $transactions,
            'selectedDepartment' => $selectedDepartment,
            'selectedType' => $selectedType,
            'selectedStatus' => $selectedStatus,
            'selectedTransaction' => $selectedTransaction,
            'selectedMinPrice' => $selectedMinPrice,
            'selectedMaxPrice' => $selectedMaxPrice,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'totalItems' => $totalItems, 
            'allAds' => $allAds, 
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
     
        return $this->render('ads/consult_ad_byId.html.twig', [
            'ad' => $ad,
            'photos'=> $photos
        ]);
    }

    #[Route ('/my-ads', name :'my_ads')]
    public function myAds (
        AdsRepository $adsRepository,
        TokenStorageInterface $tokenStorage
    ) :Response 
    {
      // Récupération de l'utilisateur actuellement authentifié
        $user = $tokenStorage->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : null;
    
        $userAds = $adsRepository->findByUserId($userId);

        return $this->render('ads/my_ads.html.twig', [
            'userAds' => $userAds,
            'user' => $user
        
        ]);
    }
    #[Route('/my-ads/edit/{adId}', name: 'app_ad_edit')]
    public function editAd(
        Request $request, 
         int $adId,
         AdsRepository $adsRepository,
         TokenStorageInterface $tokenStorage,
         EntityManagerInterface $em,
         SimpleUploadService $simpleUploadService
         ): Response
    {
        //Récupération de l'annonce à modifier
        $ad = $adsRepository->find($adId);

        //Récupération de l'utilisateur actuellement authentifié
        $user = $tokenStorage->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : null;
        // Vérification que l'utilisateur est celui qui a posté
        if ($ad->getUser()->getId() !== $userId) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }
        //Récupèration de toutes les photos
        $photos = $ad->getPhotos()->toArray();
        
       //Crée un formulaire d'annonce
        $form = $this->createForm(AdsType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photos = $request->files->all();
           
            //Seulement s'il y a eu un changement dans les photos
            if ($photos != []) {
                $images = $photos['ads']['photos'];
                foreach($images as $image){
                    $new_photos = new Photos();
                    $image_name = $image['name'];
                    $new_photo = $simpleUploadService->uploadImage($image_name);
                    $new_photos->setName($new_photo);
                    $ad->addPhoto($new_photos);
                }
            }

            $em->flush();
            $em->persist($ad);
            return $this->redirectToRoute('my_ads', ['userId' => $userId]);
        }

        return $this->render('ads/edit_ad.html.twig', [
            'form' => $form->createView(),
            'photos' => $photos
        ]);
    }

    //Pour supprimer une photo (voir js deletephotos)
    #[Route('/delete-photo/{photoId}', name: 'app_delete_photo', methods: ['DELETE'])]
    public function deletePhoto(int $photoId, EntityManagerInterface $em)
    {
        $photo = $em->getRepository(Photos::class)->find($photoId);
    
        if (!$photo) {
            throw $this->createNotFoundException('Photo non trouvée');
        }
    
        $em->remove($photo);
        $em->flush();
    
        return new JsonResponse(['message' => 'Photo supprimée avec succès'], 200);
    }
    #[Route('/my-ads/delete/{adId}', name: 'app_ad_delete', methods: ['DELETE'])]
    public function deleteAd(
        int $adId, 
        AdsRepository $adsRepository,
         EntityManagerInterface $em,
         TokenStorageInterface $tokenStorage,
         ): JsonResponse
    {
        $ad = $adsRepository->find($adId);
        $user = $tokenStorage->getToken()->getUser();
        $userId = $user instanceof User ? $user->getId() : null;
        
        if (!$ad) {
            return new JsonResponse(['message' => 'Annonce non trouvée'], 404);
        }
    
        // Supprimer l'annonce
        $em->remove($ad);
        $em->flush();
    
        return new JsonResponse(['message' => 'Annonce supprimée avec succès'], 200);
      
    }
    //Chargement des 12 prochaines annonces (pagination pour limiter lenteur)
    #[Route('/load-more-ads', name: 'load_more_ads', methods: ['GET'])]
    public function loadMoreAds(Request $request, AdsRepository $adsRepository): JsonResponse
    {
        $offset = $request->query->getInt('offset', 0);
        $limit = 12; // Limite de 12 annonces à retourner à chaque fois
        $ads = $adsRepository->findBy([], null, $limit, $offset);
        $adsArray = [];
        foreach ($ads as $ad) {
            $photos = [];
            foreach ($ad->getPhotos() as $photo) {
                $photos[] = $photo->getName();
            }
    
            $adData = [
                'id' => $ad->getId(),
                'title' => $ad->getTitle(),
                'city' => [
                    'id' => $ad->getCity()->getId(),
                    'name' => $ad->getCity()->getName()
                ],
                'type' => $ad->getType() ? $ad->getType()->getName() : null,
                'status' => $ad->getStatus() ? $ad->getStatus()->getName() : null,
                'transaction' => $ad->getTransaction() ? $ad->getTransaction()->getName() : null,
                'price' => $ad->getPrice(),
                'photos' => $photos,
            ];
            $adsArray[] = $adData;
        }
        
        return new JsonResponse($adsArray);
    }
    
}