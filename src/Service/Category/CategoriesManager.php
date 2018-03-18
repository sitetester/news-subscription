<?php

namespace App\Service\Category;

use App\Entity\NewsCategory;

class CategoriesManager
{
    private const FILE_NAME = 'categories.csv';
    private $categoriesParser;
    private $parsedCategories;

    public function __construct(CategoriesParser $categoriesParser)
    {
        $this->categoriesParser = $categoriesParser;
    }

    /**
     * @return NewsCategory[]
     */
    public function getCategories(bool $titleAsKey = false): array
    {
        if (empty($this->parsedCategories)) {
            $this->parsedCategories = $this->categoriesParser->parse(self::FILE_NAME);
        }

        $categories = [];
        foreach ($this->parsedCategories as $parsedCategory) {
            if ($titleAsKey) {
                $categories[$parsedCategory[1]] = $parsedCategory[0];
            } else {
                $categories[$parsedCategory[0]] = $parsedCategory[1];
            }
        }

        return $categories;
    }
}