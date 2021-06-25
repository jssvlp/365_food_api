<?php


namespace App\Services;


class CategoryService extends FacturascriptService
{
    public function getCategories()
    {
        return $this->get('familias');
    }
}
