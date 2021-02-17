<?php

use Bigmom\VeEditor\Http\Controllers\AuthController;
use Bigmom\VeEditor\Http\Controllers\API\ContentController as APIController;
use Bigmom\VeEditor\Http\Controllers\AssetController;
use Bigmom\VeEditor\Http\Controllers\AssetTemplateController;
use Bigmom\VeEditor\Http\Controllers\ContentController;
use Bigmom\VeEditor\Http\Controllers\FolderController;
use Bigmom\VeEditor\Http\Controllers\SceneController;
use Bigmom\VeEditor\Http\Controllers\Vapor\AssetController as VaporAssetController;
use Bigmom\VeEditor\Http\Controllers\Vapor\AssetTemplateController as VaporAssetTemplateController;
use Bigmom\VeEditor\Http\Controllers\Vapor\ContentController as VaporContentController;
use Bigmom\VeEditor\Http\Controllers\Vapor\FolderController as VaporFolderController;
use Bigmom\VeEditor\Http\Controllers\Vapor\SceneController as VaporSceneController;
use Bigmom\Auth\Http\Middleware\Authenticate;
use Bigmom\VeEditor\Http\Controllers\Vapor\SignedStorageUrlController;
use Bigmom\Auth\Http\Middleware\EnsureUserIsAuthorized;
use Illuminate\Support\Facades\Route;

Route::prefix('ve-editor')->name('ve-editor.')->middleware(['web'])->group(function () {
    Route::middleware([Authenticate::class, EnsureUserIsAuthorized::class.':ve-editor-access'])->group(function () {
        if (config('ve.main')) {

            // Redirect routes
            Route::get('/', function () {
                return redirect()->route('ve-editor.scene.getIndex');
            })->name('home');
            Route::get('/static-asset', function () {
                return redirect()->route('ve-editor.folder.getIndex', [
                    'folder-type' => 0
                ]);
            })->name('staticAsset');
            Route::get('/content-asset', function () {
                return redirect()->route('ve-editor.folder.getIndex', [
                    'folder-type' => 1
                ]);
            })->name('contentAsset');
            Route::get('/downloadable', function () {
                return redirect()->route('ve-editor.folder.getIndex', [
                    'folder-type' => 2
                ]);
            })->name('downloadable');
            // End redirect routes

            if (config('vapor.redirect_to_root') === null) {
                Route::prefix('folder')->name('folder.')->group(function () {
                    Route::get('/', [FolderController::class, 'getIndex'])->name('getIndex');
                    Route::post('/create', [FolderController::class, 'postCreate'])->name('postCreate');
                    Route::post('/update', [FolderController::class, 'postUpdate'])->name('postUpdate');
                    Route::post('/delete', [FolderController::class, 'postDelete'])->name('postDelete');
                    Route::get('{folder}/show', [FolderController::class, 'getShow'])->name('getShow');
                });
                Route::prefix('asset-template')->name('asset-template.')->group(function () {
                    Route::post('/create', [AssetTemplateController::class, 'postCreate'])->name('postCreate');
                    Route::post('/update', [AssetTemplateController::class, 'postUpdate'])->name('postUpdate');
                    Route::post('/delete', [AssetTemplateController::class, 'postDelete'])->name('postDelete');
                    Route::post('/sort', [AssetTemplateController::class, 'postSort'])->name('postSort');
                    Route::get('{template}/show', [AssetTemplateController::class, 'getShow'])->name('getShow');
                });
                Route::prefix('asset')->name('asset.')->group(function () {
                    Route::post('/create', [AssetController::class, 'postCreate'])->name('postCreate');
                    Route::post('/delete', [AssetController::class, 'postDelete'])->name('postDelete');
                });
                Route::prefix('scene')->name('scene.')->group(function () {
                    Route::get('/', [SceneController::class, 'getIndex'])->name('getIndex');
                    Route::post('/create', [SceneController::class, 'postCreate'])->name('postCreate');
                    Route::post('/update', [SceneController::class, 'postUpdate'])->name('postUpdate');
                    Route::post('/delete', [SceneController::class, 'postDelete'])->name('postDelete');
                    Route::get('{scene}/show', [SceneController::class, 'getShow'])->name('getShow');
                    Route::post('{scene}/manage', [SceneController::class, 'postManage'])->name('postManage');
                });
            } else {
                Route::prefix('folder')->name('folder.')->group(function () {
                    Route::get('/', [VaporFolderController::class, 'getIndex'])->name('getIndex');
                    Route::post('/create', [VaporFolderController::class, 'postCreate'])->name('postCreate');
                    Route::post('/update', [VaporFolderController::class, 'postUpdate'])->name('postUpdate');
                    Route::post('/delete', [VaporFolderController::class, 'postDelete'])->name('postDelete');
                    Route::get('{folder}/show', [VaporFolderController::class, 'getShow'])->name('getShow');
                });
                Route::prefix('asset-template')->name('asset-template.')->group(function () {
                    Route::post('/create', [VaporAssetTemplateController::class, 'postCreate'])->name('postCreate');
                    Route::post('/update', [VaporAssetTemplateController::class, 'postUpdate'])->name('postUpdate');
                    Route::post('/delete', [VaporAssetTemplateController::class, 'postDelete'])->name('postDelete');
                    Route::post('/sort', [VaporAssetTemplateController::class, 'postSort'])->name('postSort');
                    Route::get('{template}/show', [VaporAssetTemplateController::class, 'getShow'])->name('getShow');
                });
                Route::prefix('asset')->name('asset.')->group(function () {
                    Route::post('/create', [VaporAssetController::class, 'postCreate'])->name('postCreate');
                    Route::post('/delete', [VaporAssetController::class, 'postDelete'])->name('postDelete');
                });
                Route::prefix('scene')->name('scene.')->group(function () {
                    Route::get('/', [VaporSceneController::class, 'getIndex'])->name('getIndex');
                    Route::post('/create', [VaporSceneController::class, 'postCreate'])->name('postCreate');
                    Route::post('/update', [VaporSceneController::class, 'postUpdate'])->name('postUpdate');
                    Route::post('/delete', [VaporSceneController::class, 'postDelete'])->name('postDelete');
                    Route::get('{scene}/show', [VaporSceneController::class, 'getShow'])->name('getShow');
                    Route::post('{scene}/manage', [VaporSceneController::class, 'postManage'])->name('postManage');
                });
                Route::post('/vapor/signed-storage-url', [SignedStorageUrlController::class, 'store']);
            }
        } else if (config('app.env') != 'production'){
            Route::get('/pull', [ContentController::class, 'getIndex'])->name('getIndex');
            Route::post('/pull', [ContentController::class, 'pull'])->name('pull');
        }
    });
    Route::middleware(['api', 'auth.basic:bigmom', EnsureUserIsAuthorized::class.':ve-editor-access'])->prefix('api')->name('api.')->group(function () {
        if (config('ve.main')) {
            Route::get('/pull', [APIController::class, 'getContent'])->name('getContent');
        }
    });
    
    Route::get('/login', [AuthController::class, 'getLogin'])->name('getLogin');
    Route::post('/login', [AuthController::class, 'postLogin'])->name('postLogin');
});
