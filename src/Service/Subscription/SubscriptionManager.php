<?php

namespace App\Service\Subscription;

use App\Entity\NewsCategory;
use App\Entity\NewsSubscription;
use App\Service\Category\CategoriesManager;
use App\Service\Writer\FileWriterInterface;

class SubscriptionManager
{
    private const SUBSCRIPTION_FILE = 'subscription.csv';
    private $subscriptionParser;
    private $fileWriter;
    private $categoriesManager;

    public function __construct(
        SubscriptionParser $subscriptionParser,
        FileWriterInterface $fileWriter,
        CategoriesManager $categoriesManager)
    {
        $this->subscriptionParser = $subscriptionParser;
        $this->fileWriter = $fileWriter;
        $this->categoriesManager = $categoriesManager;
    }

    /**
     * @return NewsSubscription[]
     */
    public function loadSubscriptions(string $sortBy): ?array
    {
        $parsedSubscriptions = $this->getNewsSubscriptions();

        if ($parsedSubscriptions !== null) {
            switch ($sortBy) {
                case 'subscriberName':
                    usort($parsedSubscriptions, [NewsSubscription::class, 'compareSubscriberName']);
                    break;

                case 'subscriberEmail':
                    usort($parsedSubscriptions, [NewsSubscription::class, 'compareSubscriberEmail']);
                    break;

                default:
                    usort($parsedSubscriptions, [NewsSubscription::class, 'compareSubscriptionDate']);
            }

            return $parsedSubscriptions;
        }

        return null;
    }

    /**
     * @return NewsSubscription[]
     */
    private function getNewsSubscriptions(): ?array
    {
        return $this->subscriptionParser->parse(self::SUBSCRIPTION_FILE);
    }

    public function deleteSubscriptionByEmail(string $email): void
    {
        $parsedSubscriptions = $this->getNewsSubscriptions();
        $this->fileWriter->truncateFile(self::SUBSCRIPTION_FILE);

        foreach ($parsedSubscriptions as $key => $parsedSubscription) {
            if ($parsedSubscription->getSubscriberEmail() !== $email) {
                $this->subscribe($parsedSubscription);
            }
        }
    }

    public function subscribe(NewsSubscription $newsSubscription): void
    {
        $subscriptionDate = $newsSubscription->getSubscriptionDate()
            ? $newsSubscription->getSubscriptionDate()->format('Y-m-d H:i:s')
            : (new \DateTime())->format('Y-m-d H:i:s');

        $data = [
            $subscriptionDate,
            $newsSubscription->getSubscriberName(),
            $newsSubscription->getSubscriberEmail(),
        ];

        foreach ($newsSubscription->getNewsCategories() as $category) {
            $data[] = $category->getId() . ':' . $category->getTitle();
        }

        $this->fileWriter->writeDataToFile(self::SUBSCRIPTION_FILE, $data);
    }

    public function manageSubscription(NewsSubscription $newsSubscription): void
    {
        $categoryIds = $newsSubscription->getNewsCategories();
        $categories = $this->categoriesManager->getCategories();

        $newsCategories = [];
        foreach ($categoryIds as $categoryId) {
            $newsCategories[] = (new NewsCategory())
                ->setId($categoryId)
                ->setTitle($categories[$categoryId])
            ;
        }

        $newsSubscription->setNewsCategories($newsCategories);
        $this->subscribe($newsSubscription);
    }

    public function alreadySubscribed(string $email): bool
    {
        $parsedSubscriptions = $this->getNewsSubscriptions();
        if ($parsedSubscriptions !== null) {
            foreach ($parsedSubscriptions as $newsSubscription) {
                if ($newsSubscription->getSubscriberEmail() === $email) {
                    return true;
                }
            }
        }

        return false;
    }

    public function loadSubscriptionByEmail(string $email): NewsSubscription
    {
        $parsedSubscriptions = $this->getNewsSubscriptions();
        if ($parsedSubscriptions !== null) {
            foreach ($parsedSubscriptions as $newsSubscription) {
                if ($newsSubscription->getSubscriberEmail() === $email) {
                    return $newsSubscription;
                }
            }
        }

        throw new \InvalidArgumentException(
            sprintf('Could not find any subscription for given email: %s', $email)
        );
    }

}