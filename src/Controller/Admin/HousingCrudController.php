<?php

namespace App\Controller\Admin;

use App\Entity\Housing;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
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
        $fields = [
            // La valeur passé en paramètre à setBasePath() doit correspondre
            // à la  valeur de la clé uri_prefix dans le fichier de configuration
            // de Vich (config/packages/vich_uploader.yaml)
            ImageField::new('preview')->setBasePath('/uploads/housing'),
            TextField::new('shortDescription'),
            TextField::new('classification')->onlyOnIndex(),
            TextEditorField::new('description')->onlyWhenCreating()->onlyOnForms(),
            IntegerField::new('price'),
            IntegerField::new('roomCount')->onlyOnForms()->onlyOnDetail()->onlyWhenCreating(),
            IntegerField::new('bedroomCount')->onlyOnForms()->onlyOnDetail()->onlyWhenCreating(),
            IntegerField::new('floorArea')->onlyWhenUpdating()->onlyOnForms()->onlyWhenCreating(),
            AssociationField::new('address')->autocomplete(),
            AssociationField::new('type'),
            AssociationField::new('status'),
            AssociationField::new('images')->onlyWhenUpdating()
        ];

        return $fields;
    }
}
