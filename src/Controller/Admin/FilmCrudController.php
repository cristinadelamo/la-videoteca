<?php

namespace App\Controller\Admin;

use App\Entity\Film;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FilmCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Film::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();

        yield TextField::new('imdbtitleid','IMDb Title ID');

        yield TextField::new('title');

        yield DateField::new('datepublished', 'Date Published')
            ->setFormat('dd-MM-yyyy')
            ->hideOnIndex();

        yield TextField::new('genre');

        yield NumberField::new('duration');

        yield TextField::new('producer', 'Production Company')
            ->hideOnIndex();

        yield AssociationField::new('actors')
            ->setTemplatePath('admin/field/actors.html.twig');

        yield AssociationField::new('directors')
            ->setTemplatePath('admin/field/directors.html.twig');
    }
}