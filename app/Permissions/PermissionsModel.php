<?php

namespace App\Permissions;

class PermissionsModel
{
    // permission home pages
    static $SHOW_HOME = 'dashboard.index';
    static $SHOW_ACCOUNT = 'setting.index';
    static $LOGOUT = 'auth.logout';
    static $LOGIN = 'auth.login';


    // permission about user
    static $CREATE_USER = 'user.ajouter';
    static $UPDATE_USER = 'user.modifier';
    static $UPDATE_USER_PASSWORD = 'user.update.password';
    static $UPDATE_USER_EMAIL = 'user.update.email';
    static $UPDATE_USER_INFOS = 'user.update.informations';
    static $DROP_USER = 'user.delete';

    // permission about paroissiens
    static $LIST_PAROISSIENS = 'paroissiens.index';
    static $CREATE_PAROISSIENS = 'paroissiens.create';
    static $UPDATE_PAROISSIENS = 'paroissiens.update';
    static $DROP_PAROISSIENS = 'paroissiens.delete';
    static $PRINT_PAROISSIENS = 'paroissiens.print';
    static $LOCK_PAROISSIENS = 'paroissiens.lock';
    static $SHOW_PAROISSIENS = 'paroissiens.show';

    // permission about associations
    static $LIST_ASSOCIATION = 'association.index';
    static $CREATE_ASSOCIATION = 'association.create';
    static $UPDATE_ASSOCIATION = 'association.update';
    static $DROP_ASSOCIATION = 'association.delete';
    static $PRINT_ASSOCIATION = 'association.print';
    static $SHOW_ASSOCIATION = 'association.show';

    // permission about offrande
    static $LIST_OFFRANDE = 'association.offrande.index';
    static $CREATE_OFFRANDE = 'association.offrande.create';
    static $UPDATE_OFFRANDE = 'association.offrande.update';
    static $DROP_OFFRANDE = 'association.offrande.delete';
    static $PRINT_OFFRANDE = 'association.offrande.print';

    // permission about gestionnaire
    static $LIST_GESTIONNAIRE = 'gestionnaire.index';
    static $CREATE_GESTIONNAIRE = 'gestionnaire.create';
    static $UPDATE_GESTIONNAIRE = 'gestionnaire.update';
    static $DROP_GESTIONNAIRE = 'gestionnaire.delete';
    static $PRINT_GESTIONNAIRE = 'gestionnaire.print';

    // permission about versement
    static $LIST_VERSEMENT = 'versement.index';
    static $DETAIL_VERSEMENT_PAROISSIEN = 'versement.show';
    static $CREATE_VERSEMENT = 'versement.create';
    static $UPDATE_VERSEMENT = 'versement.update';
    static $DROP_VERSEMENT = 'versement.delete';
    static $PRINT_VERSEMENT = 'versement.print';
    // permission about cotisation
    static $LIST_COTISATION = 'cotisations.index';
    static $DETAIL_COTISATION_PAROISSIEN = 'cotisations.show';
    static $CREATE_COTISATION = 'cotisations.create';
    static $UPDATE_COTISATION = 'cotisations.update';
    static $DROP_COTISATION = 'cotisations.delete';
    static $PRINT_COTISATION = 'cotisations.print';

    // permission about engagement
    static $LIST_ENGAGEMENT = 'engagement.index';
    static $CREATE_ENGAGEMENT = 'engagement.create';
    static $UPDATE_ENGAGEMENT = 'engagement.update';
    static $DROP_ENGAGEMENT = 'engagement.delete';
    static $PRINT_ENGAGEMENT = 'engagement.print';
    static $STAT_ENGAGEMENT = 'engagement.stat';
    static $MIGRATE_ENGAGEMENT_TO_NEXT_YEAR = "engagement.migrate";

    // permission about products
    static $LIST_ROLES = 'roles.index';
    static $CREATE_ROLES = 'roles.create';
    static $UPDATE_ROLES = 'roles.update';
    static $DROP_ROLES = 'roles.delete';
    // permission about performance
    static $LIST_PERFORMANCE = 'performance.index';
    static $PRINT_PERFORMANCE = 'performance.print';




    // Array of model
    static $models = [
        "dashboard" => ["index"],
        "user" => ["ajouter","modifier","update.password","update.email","update.informations","delete"],
        "paroissiens" => ["index","create","update","delete","print","lock","show"],
        "association" => ["index","create","update","delete","show","print","offrande.index","offrande.create","offrande.update","offrande.delete","offrande.print"],
        "engagement" => ["index","create","update","delete","print","stat","migrate"],
        "roles" => ["index","create","update","delete"],
        "gestionnaire" => ["index","create","update","delete","print"],
        "versement" => ["index","create","update","delete","print","show"],
        "cotisations" => ["index","create","update","delete","show", "print"],
        "performance" => ["index","print","show", "global"],
        "settings" => ["index",'global'],
        "auth" => ["login","logout"],
        "import" => ["all"]
    ];
}
