<?php

namespace App\Controller;

use Stripe\Stripe;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Generator\UrlGenerator;

class DonationController extends AbstractController
{
    //Pour affichage de choix de somme (au click sur btn de navbar)
    #[Route('/paiement-and-donation', name: 'app_donation')]
    public function PaiementAndDonation(): Response
    {
        return $this->render('paiement/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/paiement-stripe', name: 'app_paiement-stripe')]
    public function PaiementStripe(Request $request, UrlGeneratorInterface $urlGenerator): Response
{
    if ($request->isMethod('POST')) {
        // Récuperation du token CSRF envoyé avec la requête
        $token = $request->request->get('token');

        // Vérifiez si le token CSRF est valide
        if ($this->isCsrfTokenValid('donation_token', $token)) {
            $sum = $request->request->get('sum');

            Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

            $checkout_session = Session::create([
                // 'customer_email' => $this->getUser()->getUserIdentifier(),
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $sum * 100,
                        'product_data' => [
                            'name' => 'Paiement TerraShare',
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $urlGenerator->generate('app_paiement_success_stripe', [], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $urlGenerator->generate('app_paiement_erreur_stripe', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

            return new RedirectResponse($checkout_session->url);
        } else {
            $this->addFlash('error', 'Jeton invalide');
            return $this->redirectToRoute('app_donation');
        }
    } else {
        // Redirigez vers la page de don si la méthode de la requête n'est pas POST
        return $this->redirectToRoute('app_donation');
    }
}

    #[Route('/commande/success-stripe', name: 'app_paiement_success_stripe')]
    public function stripeSuccessPaiement(): Response
    {
        return $this->render('paiement/succes_paiement.html.twig');
    }

    #[Route('/commande/erreur-stripe', name: 'app_paiement_erreur_stripe')]
    public function stripeErrorPaiement(): Response
    {
        return $this->render('paiement/erreur_paiement.html.twig');
    }
}
    