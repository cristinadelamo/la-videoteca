<?php

namespace App\Controller\Admin;

use App\Entity\Actor;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ActorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Actor::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('name');
        yield AssociationField::new('films')
            ->setTemplatePath('admin/field/films.html.twig')
            ->setFormTypeOption('disabled', $pageName === Crud::PAGE_EDIT);
        yield DateField::new('datebirth', 'Date Birth')
            ->setFormat('dd-MM-yyyy')
            ->hideOnIndex();
        yield DateField::new('datedeath', 'Date Death')
            ->setFormat('dd-MM-yyyy')
            ->hideOnIndex();
        yield TextField::new('placebirth','Place Birth')
            ->hideOnIndex();
    }
}
