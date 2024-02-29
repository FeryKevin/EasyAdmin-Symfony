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
            DateTimeField::new('startAt', 'Date de début'),
            DateTimeField::new('endAt', 'Date de fin'),
            AssociationField::new('category'),
            DateTimeField::new('createdAt')->hideOnDetail(),
            DateTimeField::new('updatedAt')->hideOnDetail(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if(!$entityInstance instanceof Project) return;

        $entityInstance->setCreatedAt(new \DateTimeImmutable);
        $entityInstance->setUpdatedAt(new \DateTimeImmutable);
        parent::persistEntity($entityManager, $entityInstance);
    }
}
