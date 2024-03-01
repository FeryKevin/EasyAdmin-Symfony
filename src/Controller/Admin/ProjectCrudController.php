<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\CrudAction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class ProjectCrudController extends AbstractCrudController
{
    public const PROJECTS_BASE_PATH = 'upload/thumbnail';
    public const PROJECTS_UPLOAD_DIR = 'public/upload/thumbnail';

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            ImageField::new('thumbnail', 'Image')
                ->setBasePath(self::PROJECTS_BASE_PATH)
                ->setUploadDir(self::PROJECTS_UPLOAD_DIR),
            TextEditorField::new('description'),
            TextField::new('link', 'Lien'),
            DateTimeField::new('startAt', 'Date de début')->setFormTypeOption('input', 'datetime_immutable'),
            DateTimeField::new('endAt', 'Date de fin')->setFormTypeOption('input', 'datetime_immutable'),
            AssociationField::new('category', 'Catégorie'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewAction = Action::new('view', 'Voir')
            ->linkToCrudAction(Action::DETAIL)
            ->setIcon('fa fa-eye');

        return $actions
            ->add(Crud::PAGE_INDEX, $viewAction)
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-edit')->setLabel('Modifier');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa fa-trash')->setLabel('Supprimer');
            });
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Project) return;

        $now = new \DateTimeImmutable();
        $entityInstance->setCreatedAt($now);
        $entityInstance->setUpdatedAt($now);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Project) return;

        $entityInstance->setUpdatedAt(new \DateTimeImmutable());
        parent::updateEntity($entityManager, $entityInstance);
    }
}
