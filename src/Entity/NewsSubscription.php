<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class NewsSubscription
{
    private $subscriptionDate;

    /**
     * @Assert\NotBlank(
     *     message="Please enter your name."
     * )
     */
    private $subscriberName;

    /**
     * @Assert\NotBlank(
     *     message="Please enter your email."
     * )
     *
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $subscriberEmail;


    /**
     * @Assert\NotBlank(
     *     message="Please select at least one category."
     * )
     */
    private $newsCategories;


    public static function compareSubscriptionDate($a, $b): int
    {
        if ($a === $b) {
            return 0;
        }

        return ($a > $b) ? +1 : -1;
    }

    public static function compareSubscriberName($a, $b): int
    {
        $al = strtolower($a->subscriberName);
        $bl = strtolower($b->subscriberName);
        if ($al === $bl) {
            return 0;
        }

        return ($al > $bl) ? +1 : -1;
    }

    public static function compareSubscriberEmail($a, $b): int
    {
        $al = strtolower($a->subscriberEmail);
        $bl = strtolower($b->subscriberEmail);
        if ($al === $bl) {
            return 0;
        }

        return ($al > $bl) ? +1 : -1;
    }

    public function getSubscriptionDate(): ?\DateTimeInterface
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(\DateTimeInterface $subscriptionDate)
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    public function getSubscriberName(): ?string
    {
        return $this->subscriberName;
    }

    public function setSubscriberName(string $subscriberName): NewsSubscription
    {
        $this->subscriberName = $subscriberName;

        return $this;
    }

    public function getSubscriberEmail(): ?string
    {
        return $this->subscriberEmail;
    }

    public function setSubscriberEmail(string $subscriberEmail): NewsSubscription
    {
        $this->subscriberEmail = $subscriberEmail;

        return $this;
    }

    /**
     * @return NewsCategory[]
     */
    public function getNewsCategories(): ?array
    {
        return $this->newsCategories;
    }

    public function setNewsCategories(array $newsCategories)
    {
        $this->newsCategories = $newsCategories;

        return $this;
    }

}