<?php

namespace App\Repository\Product;

interface ProductRepoInterface
{
    public static function getNewestProductPosts(): object;
}
