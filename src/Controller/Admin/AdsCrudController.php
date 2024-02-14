<?php

namespace App\Controller\Admin;

use App\Entity\Ads;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AdsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ads::class;
    }

}
