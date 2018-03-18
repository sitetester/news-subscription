<?php

namespace App\Service\Subscription;

use App\Entity\NewsCategory;
use App\Entity\NewsSubscription;
use App\Service\Reader\FileReaderInterface;

class SubscriptionParser
{
    private $fileReader;

    public function __construct(FileReaderInterface $fileReader)
    {
        $this->fileReader = $fileReader;
    }

    /**
     * @return NewsSubscription[]
     */
    public function parse(string $filename): ?array
    {
        $subscriptions = $this->fileReader->read($filename);

        if (\count($subscriptions)) {
            $newsSubscription = [];
            foreach ($subscriptions as $subscription) {
                $newsSubscription[] = (new NewsSubscription())
                    ->setSubscriptionDate(new \DateTime($subscription[0]))
                    ->setSubscriberName($subscription[1])
                    ->setSubscriberEmail($subscription[2])
                    ->setNewsCategories($this->parseCategories($subscription))
                ;
            }

            return $newsSubscription;
        }

        return null;
    }

    /**
     * @return NewsCategory[]
     */
    private function parseCategories(array $subscription): array
    {
        $newsCategories = [];
        $categories = \array_slice($subscription, 3);
        foreach ($categories as $category) {
            [$id, $title] = explode(':', $category);
            $newsCategories[] = (new NewsCategory())
                ->setId($id)
                ->setTitle($title)
            ;
        }

        return $newsCategories;
    }
}