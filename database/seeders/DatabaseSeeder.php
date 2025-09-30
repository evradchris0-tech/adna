public function run(): void
{
    $this->call([
        UserSeeder::class,
        RoleSeeder::class,
        PermissionSeeder::class,
        AssociationSeeder::class,
        ParoissienSeeder::class,
        EngagementSeeder::class,
        VersementSeeder::class,
        CotisationSeeder::class,
        OffrandeSeeder::class,
        GestionnaireSeeder::class,
    ]);
}
