<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // La valeur passé en paramètre à setBasePath() doit correspondre
        // à la  valeur de la clé uri_prefix dans le fichier de configuration
        // de Vich (config/packages/vich_uploader.yaml)
        $image = ImageField::new('url')->setBasePath('/uploads/housing');
        $imageFile = ImageField::new('imageFile')->setFormType(VichImageType::class);

        $fields =  [
            AssociationField::new('housing')->autocomplete(),
            TextField::new('alt')->setLabel('Description')
        ];

        if($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL)
        {
            $fields[] = $image;
        } else {
            $fields[] = $imageFile;
        }

        return $fields;
    }
}
