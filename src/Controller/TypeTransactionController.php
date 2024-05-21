<?php
namespace App\Controller;

use App\Repository\TypeTransactionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeTransactionController extends AbstractController
{
    #[Route('/get-transactions/{typeId}', name:'app_get_transactions')]
    public function getTransaction($typeId, TypeTransactionRepository $TypeTransactionRepository) :JsonResponse {
 
    $TypeTransactions = $TypeTransactionRepository->findBy(['Type'=>$typeId]);
   
    $transactions = [];

    foreach($TypeTransactions as $typeTransaction) {
      
        $transactions[] = [
            'id' => $typeTransaction->getTransaction()->getId(),
            'name' => $typeTransaction->getTransaction()->getName(),
        ];
      
    }
    return new JsonResponse($transactions);
    }

}
