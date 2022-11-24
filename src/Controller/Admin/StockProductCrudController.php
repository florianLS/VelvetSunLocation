<?php

namespace App\Controller\Admin;

use App\Entity\StockProduct;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class StockProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return StockProduct::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('product'),
            IntegerField::new('stock_number')
        ];
    }
}
