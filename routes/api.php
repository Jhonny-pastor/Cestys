<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\Admin\CourseController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\GradeController;

use App\Http\Controllers\Api\V1\Admin\CategoryController;
use App\Http\Controllers\Api\V1\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Api\V1\Admin\ModuleController;
use App\Http\Controllers\Api\V1\Admin\TopicController;
use App\Http\Controllers\Api\V1\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\EnrollmentController;
use App\Http\Controllers\Api\V1\Admin\TransactionController;
use App\Http\Controllers\Api\V1\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Api\V1\Admin\UserController;
use App\Http\Controllers\Api\V1\Admin\MediaController;
use App\Http\Controllers\Api\V1\Admin\StatController;

Route::prefix('v1')->group(function () {

    // =========================
    // AUTH
    // =========================
    Route::middleware(['jwt.auth', 'admin'])->group(function () {
        Route::get('admin/certificates', [AdminCertificateController::class, 'index']);
        Route::get('admin/certificates/{id}', [AdminCertificateController::class, 'show']);
        Route::post('admin/certificates', [AdminCertificateController::class, 'store']);
        Route::put('admin/certificates/{id}', [AdminCertificateController::class, 'update']);
        Route::delete('admin/certificates/{id}', [AdminCertificateController::class, 'destroy']);
    });


    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::get('auth/me', [AuthController::class, 'me'])->middleware('jwt.auth');
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('auth/logout', [AuthController::class, 'logout'])->middleware('jwt.auth');

    // =========================
    // PUBLIC
    // =========================
    Route::get('courses', [CourseController::class, 'index']);
    Route::get('courses/{id}', [CourseController::class, 'show']);
    Route::get('certificates/validate/{code}', [CertificateController::class, 'validate']);

    // =========================
    // ORDERS / PAYMENTS
    // =========================
    Route::post('orders', [OrderController::class, 'store']);
    Route::post('payments/webhook', [PaymentController::class, 'webhook']);

    // =========================
    // STUDENT (JWT)
    // =========================
    Route::middleware('jwt.auth')->group(function () {
        Route::get('me/enrollments', [StudentController::class, 'enrollments']);
        Route::get('courses/{id}/content', [StudentController::class, 'courseContent']);
        Route::patch('progress/topics/{temaId}', [StudentController::class, 'updateTopicProgress']);

        Route::get('me/orders', [OrderController::class, 'myOrders']);
        Route::get('orders/{id}/status', [OrderController::class, 'orderStatus']);
        Route::post('orders/{id}/retry', [OrderController::class, 'retryOrder']);

        Route::get('me/certificates', [CertificateController::class, 'myCertificates']);
        Route::get('me/certificates/{id}/download', [CertificateController::class, 'downloadCertificate']);
        Route::post('me/certificates/{id}/email', [CertificateController::class, 'emailCertificate']);
    });

    // =========================
    // GRADES (MIXTO)
    // =========================
    Route::post('courses/{courseId}/grade/auto', [GradeController::class, 'autoGrade']);

    // OJO: adminIndex/adminUpdate deben estar protegidos por admin,
    // así que van en el grupo admin de abajo.

    // =========================
    // ADMIN (JWT + ADMIN)
    // =========================
    Route::middleware(['jwt.auth', 'admin'])->group(function () {

        // GRADES (ADMIN)
        Route::get('admin/grades', [GradeController::class, 'adminIndex']);
        Route::put('admin/grades/{id}', [GradeController::class, 'adminUpdate']);

        // CRUD categories
        Route::apiResource('admin/categories', CategoryController::class);

        // CRUD courses
        Route::apiResource('admin/courses', AdminCourseController::class);

        // CRUD modules
        Route::apiResource('admin/modules', ModuleController::class);

        // CRUD topics
        Route::apiResource('admin/topics', TopicController::class);

        // Otros endpoints admin
        Route::get('admin/students', [AdminStudentController::class, 'index']);
        Route::get('admin/admins', [AdminController::class, 'index']);
        Route::get('admin/enrollments', [EnrollmentController::class, 'index']);
        Route::get('admin/transactions', [TransactionController::class, 'index']);
        Route::get('admin/certificates', [AdminCertificateController::class, 'index']);

        Route::patch('admin/users/{id}/role', [UserController::class, 'updateRole']);
        Route::post('admin/media/upload', [MediaController::class, 'upload']);
        Route::get('admin/stats/summary', [StatController::class, 'summary']);
    });
});
