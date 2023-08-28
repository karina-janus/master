<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryService implements CategoryServiceInterface
{
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function save(Category $category): void
    {
        if (null === $category->getId()) {
            $category->setCreatedAt(new \DateTimeImmutable());
        }
        $category->setUpdatedAt(new \DateTimeImmutable());

        $this->categoryRepository->save($category, true);
    }
}