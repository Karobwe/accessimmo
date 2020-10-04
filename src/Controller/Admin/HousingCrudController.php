<?php

namespace App\Controller\Admin;

use App\Entity\Housing;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class HousingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Housing::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('shortDescription'),
            TextEditorField::new('description'),
            IntegerField::new('price'),
            IntegerField::new('roomCount'),
            IntegerField::new('bedroomCount'),
            IntegerField::new('floorArea'),
            AssociationField::new('address')->autocomplete(),
            AssociationField::new('type'),
            AssociationField::new('status'),
            AssociationField::new('images')
            // price, roomCount, bedroomCount, floorArea, address, type, status, images
        ];
    }
}
