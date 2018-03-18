<?php

namespace App\Controller;

use App\Entity\NewsSubscription;
use App\Form\NewsSubscriptionType;
use App\Service\Subscription\SubscriptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends Controller
{
    private $subscriptionManager;

    public function __construct(SubscriptionManager $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    /**
     * Guest user can subscribe
     * Login check is applied based on `/subscription` route, which doesn't apply here
     * See `access_control` under /config/packages/security.yaml
     *
     * @Route("/subscribe", name="subscription_subscribe")
     */
    public function subscribe(Request $request): Response
    {
        $form = $this->createForm(NewsSubscriptionType::class, new NewsSubscription());
        $form->handleRequest($request);

        $error = '';

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var NewsSubscription $newsSubscription */
            $newsSubscription = $form->getData();

            if (!$this->subscriptionManager->alreadySubscribed($newsSubscription->getSubscriberEmail())) {
                $this->subscriptionManager->manageSubscription($newsSubscription);

                $message = 'News subscribed successfully.';
                $redirectToRoute = 'subscription_list';

                if (!$this->getUser()) {
                    $message .= ' Please login to see/modify subscription.';
                    $redirectToRoute = 'login';
                }

                $this->addFlash('notice', $message);

                return $this->redirectToRoute($redirectToRoute);
            }

            $error = 'User already subscribed with this email.';
        }

        return $this->render(
            'subscription/subscribe.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    /**
     * Only logged in user can edit subscription
     * See `access_control` under /config/packages/security.yaml
     *
     * @Route("/subscription/edit/{subscriberEmail}", name="subscription_edit")
     */
    public function edit(Request $request, string $subscriberEmail): Response
    {
        $newsSubscription = $this->subscriptionManager->loadSubscriptionByEmail($subscriberEmail);

        $form = $this->createForm(NewsSubscriptionType::class, $newsSubscription);
        $form->handleRequest($request);

        $error = '';
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var NewsSubscription $newsSubscription */
            $postedNewsSubscription = $form->getData();

            // suppose subscriber changed his email
            // someone else subscribed with same email ?
            if (
                $subscriberEmail !== $postedNewsSubscription->getSubscriberEmail()
                && $this->subscriptionManager->alreadySubscribed($postedNewsSubscription->getSubscriberEmail())) {
                $error = 'User already subscribed with this email.';
            } else {
                // delete existing subscription
                $this->subscriptionManager->deleteSubscriptionByEmail($subscriberEmail);
                $this->subscriptionManager->manageSubscription($newsSubscription);

                $this->addFlash(
                    'notice',
                    'News subscription updated successfully.'
                );

                return $this->redirectToRoute('subscription_list');
            }
        }

        return $this->render(
            'subscription/subscribe.html.twig', ['form' => $form->createView(),
            'error' => $error]);
    }

    /**
     * @Route("/subscription/list/sortBy/{sortBy}", name="subscription_list", defaults={"sortBy"="subscriptionDate"})
     */
    public function list(string $sortBy): Response
    {
        return $this->render(
            'subscription/list.html.twig', [
                'subscriptions' => $this->subscriptionManager->loadSubscriptions($sortBy)
            ]
        );
    }

    /**
     * @Route("/subscription/delete/{subscriptionEmail}", name="subscription_delete")
     */
    public function delete(string $subscriptionEmail)
    {
        $this->subscriptionManager->deleteSubscriptionByEmail($subscriptionEmail);

        return $this->redirectToRoute('subscription_list');
    }
}