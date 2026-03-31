<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\Admin\BulkSmsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\Currency\CurrencyController;
use App\Http\Controllers\Admin\FinanceEntryController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\StudentApplicationController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentInvoiceController;
use App\Http\Controllers\Admin\Wallet\WalletController;
use App\Http\Controllers\Admin\Wallet\WalletTransactionController;
use App\Http\Controllers\Admin\Wallet\WalletTransferController;
use App\Http\Controllers\FileManagerController;
// use App\Http\Controllers\post\PostController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\SuccessStoryController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use Symfony\Component\HttpFoundation\Response;

// Notification read and redirect
Route::get('notifications/{id}/read-redirect', [NotificationController::class, 'readAndRedirect'])->name('notifications.readRedirect');

// Notification AJAX read route
Route::post('notifications/{id}/ajax-read', [App\Http\Controllers\NotificationController::class, 'ajaxRead'])->name('notifications.ajaxRead');

// test Route

Route::get('filemanager', [FileManagerController::class, 'index']);
Route::post('filemanager/create-directory', [FileManagerController::class, 'createDirectory']);
Route::get('filemanager/initialize', [FileManagerController::class, 'initialize']);

// end test route

Auth::routes([
    'login'    => true,
    'logout'   => true,
    'register' => true,
    'reset'    => true,   // for resetting passwords
    'confirm'  => false,  // for additional password confirmations
    'verify'   => false,  // for email verification
]);
Route::get('application-form', [StudentController::class, 'applicationForm']);
Route::post('application-form', [StudentController::class, 'applicationFormSubmit'])->name('application-form.store');
Route::get('application-show/{slug}', [StudentController::class, 'applicationShow'])->name('application.show');
Route::get('success-story-of-areducation/{slug}', [SuccessStoryController::class, 'show'])->name('successstory.show');


Route::get('/', [DashboardController::class, 'logincheck']);
Route::middleware('auth')->group(function () {
    Route::get('/home', [DashboardController::class, 'logincheck']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('user-management', UserController::class);
    Route::resource('branches', BranchController::class);
    Route::resource('posts', PostController::class);
    Route::resource('pages', PostController::class);
    Route::resource('sliders', PostController::class);
    Route::resource('services', PostController::class);
    Route::resource('testimonials', PostController::class);
    Route::resource('events', PostController::class);
    Route::resource('success-story', PostController::class);
    Route::resource('universities', PostController::class);
    Route::resource('videos', PostController::class);
    Route::resource('todo', TodoController::class);
    Route::resource('task', TaskController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('tag', TagController::class);
    Route::resource('inquiries', InquiryController::class);
    Route::resource('students', StudentController::class);
    Route::resource('finance_entries', FinanceEntryController::class);
    Route::get('finance_entries-export', [FinanceEntryController::class, 'export'])->name('finance_entries.export');

    // Common route
    Route::get('/sms_template/{id}', [CommonController::class, 'getSmsTemplate'])->name('getSmsTemplate');
    Route::get('/sms_templates', [CommonController::class, 'getSmsTemplates'])->name('getSmsTemplates');
    Route::get('/export-posts-seeder', [PostController::class, 'exportSeeder'])->name('exportSeeder');
    // Route::resource('test-import-execl', TestController::class);
    Route::resource('test-import-execl', TestController::class);
    Route::get('test-income-cost', [TestController::class, 'testIncomeCost']);
    Route::get('test-income', [TestController::class, 'testIncome']);
    Route::get('import-inquiry', [TestController::class, 'importInquiry']);
    Route::get('import-inquiry-rajshahi2', [TestController::class, 'importInquiryRajshahi2']);


    Route::group(['prefix'=>'setting', 'as' => 'setting.'], function(){
        Route::resource('products', SettingController::class);
        Route::resource('expenses', SettingController::class);
        Route::resource('documents', SettingController::class);
        Route::resource('roles', SettingController::class);
        Route::resource('studies', SettingController::class);
        Route::resource('subjects', SettingController::class);
        Route::resource('student_status', SettingController::class);
        Route::resource('university_status', SettingController::class);
        Route::resource('sms_template', SettingController::class);

    });

    Route::resource('currencies', CurrencyController::class);
    Route::get('currencies/{currency}/rates', [CurrencyController::class, 'rates'])->name('currencies.rates');
    Route::post('currencies/{currency}/rates', [CurrencyController::class, 'addRate'])->name('currencies.rates.store');

    // Wallets
    Route::resource('wallets', WalletController::class);

    // Wallet Transactions (nested)
    Route::get('wallets/{wallet}/transactions', [WalletTransactionController::class, 'index'])->name('wallets.transactions.index');

    // Wallet Transfer
    Route::get('wallet/transfer', [WalletTransferController::class, 'create'])->name('wallets.transfer.form');
    Route::post('wallet/transfer', [WalletTransferController::class, 'store'])->name('wallets.transfer');

    Route::group(['prefix'=>'finance_entrie', 'as' => 'finance_entries.'], function(){
        Route::resource('invoices', StudentInvoiceController::class);
        Route::resource('reports', ReportsController::class);
    });
    Route::group(['prefix'=>'student', 'as' => 'student.'], function(){
        Route::resource('invoices', StudentInvoiceController::class);
        Route::resource('application', StudentApplicationController::class);

        Route::get('/send_single_sms', [BulkSmsController::class, 'sendSingleSms'])->name('sendSingleSms');
        Route::get('/send_bulk_sms', [BulkSmsController::class, 'sendBulkSms'])->name('sendBulkSms');
        Route::get('/send_bulk_sms_many', [BulkSmsController::class, 'sendBulkSmsMany'])->name('sendBulkSmsMany');
    });
    Route::group(['prefix'=>'my-profile', 'as' => 'my-profile.'], function(){
        Route::get('/', [MyProfileController::class, 'index'])->name('index');
        Route::get('/basic', [MyProfileController::class, 'basicEdit'])->name('basic_edit');
        Route::post('/basic/update', [MyProfileController::class, 'basicUpdate'])->name('basic_update');
        Route::get('/image', [MyProfileController::class, 'imageUpload'])->name('imageUpload');
        Route::post('/image/update', [MyProfileController::class, 'imageUpdate'])->name('image_update');
        Route::get('/change-password', [MyProfileController::class, 'changePassword'])->name('changepassword');
        Route::post('/update-password', [MyProfileController::class, 'updatePassword'])->name('updatepassword');
    });

    // Messaging System
    Route::prefix('messages')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unreadCount');
        Route::get('/search-users', [MessageController::class, 'searchUsers'])->name('messages.searchUsers');
        Route::get('/recent', [MessageController::class, 'recent'])->name('messages.recent');
        Route::post('/start', [MessageController::class, 'startConversation'])->name('messages.start');
        Route::get('/{conversation}', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/{conversation}', [MessageController::class, 'store'])->name('messages.store');
        Route::post('/{conversation}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
        Route::get('/{conversation}/poll', [MessageController::class, 'poll'])->name('messages.poll');
    });
});

Route::get('/run_migration/{key?}', [
    // /run_migration/m!n@r
    function($key = null) {
        if ($key == "m!n@r") {
            try {
                echo 'Initilizing Migration';
                Artisan::call('migrate', [
                    '--force' => true,
                ]);
                Artisan::output();
                echo '<br/>All files migrated.';
            } catch (Exception $e) {
                Response::make($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }
]);
Route::get('/run_seeder/{key?}', [
    function($key = null) {
        if ($key == "m!n@r") {
            try {
                echo 'Initilizing Seeder';
                \Artisan::call('db:seed', [
                    '--force' => true,
                ]);
                \Artisan::output();
                echo '<br/>All files Seeded.';
            } catch (Exception $e) {
                Response::make($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }
]);

Route::get('/run_migration_rollback/{key?}', [
    function($key = null) {
        if ($key == "m!n@r") {
            try {
                echo 'Initilizing Migration rollback';
                \Artisan::call('migrate:rollback --step=1');
                \Artisan::output();
                echo '<br/>All files rollback migrated.';
            } catch (Exception $e) {
                Response::make($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }
]);

Route::get('/all_cache_clear/{key?}', [
    // /all_cache_clear/m!n@r
    function($key = null) {
        if ($key == "m!n@r") {
            try {
                echo 'Initilizing cache clear';
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('route:clear');
                \Artisan::output();
                echo '<br/>All all cache cleared';
            } catch (Exception $e) {
                Response::make($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }
]);

// Storage symlink route (for admin use only, remove after use)
Route::get('/link_storage/{key?}', [
    function($key = null) {
        if ($key == "m!n@r") {
            try {
                \Artisan::call('storage:link');
                echo 'Storage symlink created!';
            } catch (Exception $e) {
                return response($e->getMessage(), 500);
            }
        } else {
            App::abort(404);
        }
    }
]);


