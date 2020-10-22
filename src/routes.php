<?php

use Bigmom\VeEditor\Http\Controllers\API\ContentController as APIController;
use Bigmom\VeEditor\Http\Controllers\AssetController;
use Bigmom\VeEditor\Http\Controllers\AssetTemplateController;
use Bigmom\VeEditor\Http\Controllers\ContentController;
use Bigmom\VeEditor\Http\Controllers\FolderController;
use Bigmom\VeEditor\Http\Controllers\SceneController;
use Bigmom\VeEditor\Http\Controllers\PointTemplateController;
use Bigmom\VeEditor\Http\Controllers\TagController;
use Bigmom\VeEditor\Http\Middleware\EnsureUserIsAuthorized;
use Illuminate\Support\Facades\Route;

Route::prefix('ve-editor')->name('ve-editor.')->group(function () {
    Route::middleware(['web', 'auth:sanctum', EnsureUserIsAuthorized::class])->group(function () {
        if (config('ve.main')) {
            Route::get('/', function () {
                return redirect()->route('ve-editor.scene.getIndex');
            })->name('home');
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
        } else if (config('app.env') != 'production'){
            Route::get('/pull', [ContentController::class, 'getIndex'])->name('getIndex');
            Route::post('/pull', [ContentController::class, 'pull'])->name('pull');
        }
    });
    Route::middleware(['api', 'auth:sanctum', EnsureUserIsAuthorized::class])->prefix('api')->name('api.')->group(function () {
        if (config('ve.main')) {
            Route::get('/pull', [APIController::class, 'getContent'])->name('getContent');
        }
    });
});
