<?php

use App\Http\Controllers\AssociationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CotisationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\GestionnaireController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\OffrandeController;
use App\Http\Controllers\ParoissienController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\VersementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAssociationsController;
use App\Http\Controllers\AssociationSwitchController;

use function Termwind\render;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/recover', [AuthController::class, 'recoverView'])->name('recover');
    Route::post('/recover', [AuthController::class, 'recover'])->name('recoverAction');
    Route::get('/reinitialise/{token}', [AuthController::class, 'reinitialiseView'])->name('reinitialiser');
    Route::post('/reinitialise/{token}', [AuthController::class, 'reinitialise'])->name('reinitialiserAction');
    Route::get('/{name}/verify/email/{token}/{user_id}', [AuthController::class, 'verify'])->name('verify');
    Route::get('/', [AuthController::class, 'loginView']);
    Route::get('/login', [AuthController::class, 'loginView'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware(['auth', 'permission'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('index');
    });
    Route::prefix('associations')->name('association.')->group(function () {
        Route::get('', [AssociationController::class, 'index'])->name('index');
        Route::get('/pdf', [AssociationController::class, 'downlodPdf'])->name('print');
        Route::prefix('{id}/offrandes')->name('offrande.')->group(function () {
            Route::get('', [OffrandeController::class, 'index'])->name('index');
        });
        Route::prefix('user/associations')->name('user.associations.')->group(function () {
            Route::post('/switch', [AssociationSwitchController::class, 'switch'])->name('switch');
            Route::post('/set-primary', [AssociationSwitchController::class, 'setPrimary'])->name('setPrimary');
        });
    });
    Route::prefix('engagements')->name('engagement.')->group(function () {
        Route::get('', [EngagementController::class, 'index'])->name('index');
        Route::get('/ajout', [EngagementController::class, 'create'])->name('create');
        Route::get('/update/{id}', [EngagementController::class, 'updateView'])->name('update');
        Route::get('/pdf', [EngagementController::class, 'downlodPdf'])->name('print');
        Route::get('/migrate', [EngagementController::class, 'migrate'])->name('migrate');
    });
    Route::prefix('gestionnaire')->name('gestionnaire.')->group(function () {
        Route::get('', [GestionnaireController::class, 'index'])->name('index');
        Route::get('/pdf', [GestionnaireController::class, 'downlodPdf'])->name('print');
    });
    Route::prefix('paroissiens')->name('paroissiens.')->group(function () {
        Route::get('', [ParoissienController::class, 'index'])->name('index');
        Route::get('/pdf', [ParoissienController::class, 'downlodPdf'])->name('print');
        Route::get('ajout', [ParoissienController::class, 'create'])->name('create');
        Route::post('ajout', [ParoissienController::class, 'insert']);
        Route::get('{id}', [ParoissienController::class, 'show'])->name('show');
        Route::get('update/{id}', [ParoissienController::class, 'updateView'])->name('update');
        Route::post('update/{id}', [ParoissienController::class, 'update']);
        Route::delete('{id}', [ParoissienController::class, 'delete'])->name('remove');
    });
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('', [SettingsController::class, 'index'])->name('index');
        Route::post('', [SettingsController::class, 'globalUpdate'])->name('global');
    });
    Route::prefix('user')->name('user.update.')->group(function () {
        Route::post('', [SettingsController::class, 'update'])->name('informations');
        Route::post('/password', [SettingsController::class, 'updatePwd'])->name('password');
        Route::post('/profil', [SettingsController::class, 'updateProfil'])->name('email');
    });
    Route::prefix('cotisations')->name('cotisations.')->group(function () {
        Route::get('/pdf', [CotisationController::class, 'downlodPdf'])->name('print');
        Route::get('', [CotisationController::class, 'index'])->name('index');
        Route::get('/ajout', [CotisationController::class, 'create'])->name('create');
        Route::get('paroissien/{id}/detail', [CotisationController::class, 'show'])->name('show');
        Route::get('/update/{id}', [CotisationController::class, 'updateView'])->name('update');
    });
    Route::prefix('versements')->name('versement.')->group(function () {
        Route::get('', [VersementController::class, 'index'])->name('index');
        Route::get('/pdf', [VersementController::class, 'downlodPdf'])->name('print');
        Route::get('/ajout', [VersementController::class, 'create'])->name('create');
        Route::get('paroissien/{id}/detail', [VersementController::class, 'show'])->name('show');
        Route::get('/update/{id}', [VersementController::class, 'updateView'])->name('update');
    });
    Route::prefix('performance')->name('performance.')->group(function () {
        Route::get('/pdf', [PerformanceController::class, 'downlodPdf'])->name('print');
        Route::get('detail/{id}', [PerformanceController::class, 'show'])->name('show');
        Route::get('global', [PerformanceController::class, 'show_global'])->name('global');
        Route::get('', [PerformanceController::class, 'index'])->name('index');
    });

    // roles routes
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('', [RolesController::class, 'index'])->name('index');
    });

    Route::prefix('import')->name('import.')->group(function () {
        Route::post('', [ImportController::class, 'index'])->name('all');
    });


    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::middleware(['auth', 'check.association'])->group(function () {

        // Gestion des associations de l'utilisateur
        Route::prefix('user/associations')->name('user.associations.')->group(function () {
            Route::get('/', [UserAssociationsController::class, 'index'])->name('index');
            Route::post('/attach', [UserAssociationsController::class, 'attach'])->name('attach');
            Route::delete('/detach', [UserAssociationsController::class, 'detach'])->name('detach');
            Route::post('/set-primary', [UserAssociationsController::class, 'setPrimary'])->name('set-primary');
            Route::post('/update-role', [UserAssociationsController::class, 'updateRole'])->name('update-role');
        });

        // Changement d'association active (déjà existant, mais je le mets pour référence)
        Route::post('/switch-association', [AssociationSwitchController::class, 'switch'])->name('association.switch');
        Route::post('/set-primary-association', [AssociationSwitchController::class, 'setPrimary'])->name('association.set-primary');
    });
});
