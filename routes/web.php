<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ConexionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FormValueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});
/* ->middleware('auth'); */
Auth::routes();
Route::group(['middleware' => 'App\Http\Middleware\StartSession'], function () {
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::post('/obtener-datos', [ConexionController::class, 'obtenerDatos']);


Route::resource('forms', FormController::class)->except(['show']);
Route::get('/forms/fill/{id}', [FormController::class, 'fill'])->name('forms.fill')->middleware(['auth']);
Route::post('ckeditors/upload', [FormController::class, 'ckupload'])->name('ckeditors.upload')->middleware(['auth']);
Route::post('dropzone/upload/{id}', [FormController::class, 'dropzone'])->name('dropzone.upload')->middleware(['Setting']);
Route::post('form/status/{id}', [FormController::class, 'formStatus'])->name('form.status');
Route::get('/forms/grid/{id?}', [FormController::class, 'grid_view'])->name('grid.form.view');
Route::get('design/{id}', [FormController::class, 'design'])->name('forms.design')->middleware(['auth']);
Route::put('/forms/design/{id}', [FormController::class, 'designUpdate'])->name('forms.design.update')->middleware(['auth']);
Route::post('ckeditor/upload', [FormController::class, 'upload'])->name('ckeditor.upload')->middleware(['auth']);
Route::get('/form-values/{id}/download/pdf', [FormValueController::class, 'download_pdf'])->name('download.form.values.pdf')->middleware(['auth']);

Route::put('/forms/fill/{id}', [FormController::class, 'fillStore'])->name('forms.fill.store');
Route::get('/form-values/{id}/edit', [FormValueController::class, 'edit'])->name('edit.form.values');
Route::get('/form-values/{id}/view', [FormValueController::class, 'showSubmitedForms'])->name('view.form.values');
Route::resource('formvalues', FormValueController::class);

Route::post('files/video/store', [FormValueController::class, 'VideoStore'])->name('videostore')->middleware(['xss']);


Route::resource('profile', ProfileController::class);

// Cargar las rutas desde la carpeta "routes/web" y sus subcarpetas

$routeFiles = File::allFiles(base_path('routes/web'));

foreach ($routeFiles as $file) {
    if ($file->getExtension() == 'php') {
        require $file;
    }
}