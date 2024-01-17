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
use App\Repository\UserRepository;
use App\Repository\CitiesRepository;
use App\Repository\PhotosRepository;
use App\Services\SimpleUploadService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartmentsRepository;
use App\Repository\StatusRepository;
use App\Repository\TransactionRepository;
use App\Repository\TypeRepository;
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
                    $new_photo = $simpleUploadService->uploadImage($image_name);
                    $new_photos->setName($new_photo);
                    $ad->addPhoto($new_photos);
                     
                    $ad->setCity($form->get('city')->getData());
                    
                    $em->persist($ad);
                    $em->flush();
                    // Redirigez vers une page de confirmation ou ailleurs
                    return $this->redirectToRoute('app_home');
                }
            } else{
                $errorMessage = 'Postez au moins une photo SVP';
            }

        }

        // Affichez le formulaire dans le template
        return $this->render('ads/create_ad.html.twig', [
            'form' => $form->createView(),
            'city' => $city,
            'errorMessage' => $errorMessage
        ]);
    }
    //voir Annonces
    #[Route('/consult-ads', name: 'app_consult_ads')]
    public function consultAds(
        Request $request,
        AdsRepository $adsRepository,
        DepartmentsRepository $departmentsRepository,
        PhotosRepository $photosRepository,
        TypeRepository $typeRepository,
        TransactionRepository $transactionRepository,
        StatusRepository $statusRepository): Response
    {

        $ads = $adsRepository->findAll();
      
        $departments = $departmentsRepository->findBy([], ['number' => 'ASC']);    
        $selectedDepartment = $request->query->get('department', null);

        $types = $typeRepository->findAll();
        $selectedType = $request->query->get('type',null);
         
        $status = $statusRepository->findAll();
        $selectedStatus = $request->query->get('status',null);

        $transactions =$transactionRepository->findAll();
        $selectedTransaction= $request->query->get('transaction',null);

        if ($selectedDepartment) {
            // Appliquez le filtre par département
            $ads = $adsRepository->findByFilters($selectedDepartment,$selectedType,$selectedStatus,$selectedTransaction);
            // Si aucun résultat n'est trouvé, redirigez vers la même page sans le paramètre de département
        }
      
        return $this->render('ads/consult_ads.html.twig', [
            'ads' => $ads,
            'departments' => $departments,
            'selectedDepartment' => $selectedDepartment,
            'types'=>$types,
            'selectedType'=> $selectedType,
            'transactions'=>$transactions,
            'selectedTransaction'=> $selectedTransaction,
            'status'=>$status,
            'selectedStatus'=> $selectedStatus
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
     
        return $this->render('ads/consult_ad_byAdId.html.twig', [
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
      // Récupérer l'utilisateur actuellement authentifié
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
        //Récup de l'annonce à modifier
        $ad = $adsRepository->find($adId);
        //Récup de l'utilisateur en cours
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
}