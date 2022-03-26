<?php

namespace App\Controller\Admin;

use App\Entity\Director;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DirectorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Director::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('name');
        yield AssociationField::new('films')
            ->setFormTypeOption('choice_label', 'title')
            ->formatValue(function ($value, $entity) {
                $str = $entity->getFilms()[0];
                for ($i = 1; $i < $entity->getFilms()->count(); $i++) {
                    $str = $str . ", " . $entity->getFilms()[$i];
                }
                return $str;
            });
        yield DateField::new('datebirth', 'Date Birth')
            ->setFormat('dd-MM-yyyy')
            ->hideOnIndex();
    }
}
