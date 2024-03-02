<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\Technology;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\CrudAction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextBlockField;

class ProjectCrudController extends AbstractCrudController
{
    public const PROJECTS_BASE_PATH = 'upload/thumbnail';
    public const PROJECTS_UPLOAD_DIR = 'public/upload/thumbnail';

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Liste des projets');
    }

    public function configureFields(string $pageName): iterable
    {
        $technologiesField = AssociationField::new('technologies', 'Technologies')
            ->onlyOnForms()
            ->setFormTypeOptions([
                'by_reference' => false,
                'multiple' => true,
                'class' => Technology::class,
                'choice_label' => 'name',
                'required' => 'true',
            ]);

        if (Crud::PAGE_DETAIL === $pageName) {
            $technologiesField = ArrayField::new('technologies', 'Technologies')
                ->onlyOnDetail();
        }

        if (Crud::PAGE_INDEX === $pageName) {
            $technologiesField = CollectionField::new('technologies', 'Technologies')
                ->onlyOnIndex();
        }

        $fields = [
            TextField::new('name', 'Nom'),
            ImageField::new('thumbnail', 'Image')
                ->setBasePath(self::PROJECTS_BASE_PATH)
                ->setUploadDir(self::PROJECTS_UPLOAD_DIR),
            AssociationField::new('category', 'Catégorie')
                ->setRequired(true),
            $technologiesField,
        ];

        if (in_array($pageName, [Crud::PAGE_DETAIL, Crud::PAGE_NEW, Crud::PAGE_EDIT])) {
            $fields[] = DateTimeField::new('startAt', 'Date de début')->setFormTypeOption('input', 'datetime_immutable');
            $fields[] = DateTimeField::new('endAt', 'Date de fin')->setFormTypeOption('input', 'datetime_immutable');
            $fields[] = DateTimeField::new('createdAt', 'Date de création')->onlyOnDetail();
            $fields[] = DateTimeField::new('updateAd', 'Date de modification')->onlyOnDetail();
            $fields[] = TextField::new('link', 'Lien');
            $fields[] = TextEditorField::new('description');
        }

        return $fields;
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
