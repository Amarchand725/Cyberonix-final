<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\BankAccountController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\GrievanceController;
use App\Http\Controllers\Admin\InventoryCategoryController;
use App\Http\Controllers\Admin\IpManagementController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\DocumentController;
use App\Models\InventoryCategory;
use Illuminate\Support\Facades\Cookie;
use Twilio\Rest\Chat\V1\Service\RoleContext;

// use Twilio\Rest\Client;

use function Pest\Laravel\json;

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
Route::get('/pre_employee/create', [App\Http\Controllers\PreEmployeeController::class, 'create'])->name('pre_employee.create');
Route::post('/pre_employee/store', [App\Http\Controllers\PreEmployeeController::class, 'store'])->name('pre_employee.store');

    Route::get("check-rocket-flare-users", function () {
        return checkRocketFlareUser();
    });
    Route::get("uid", function () {
        return generateAssetUID("Laptop 12inch", 1);
    });

    Route::get("check-rocket-flare-users", function () {
        return checkRocketFlareUser();
    });
    Route::get("uid", function () {
        return generateAssetUID("Laptop 12inch", 1);
    });

    Route::get('/cache-clear', function () {
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        $cache = 'Route cache cleared <br /> View cache cleared <br /> Cache cleared <br /> Config cleared <br /> Config cache cleared';
        return $cache;
    });

    Route::get('/pre_employees/office_boys/create', [App\Http\Controllers\PreEmployeeController::class, 'createForm'])->name('office_boys.createForm');
    Route::post('/pre_employees/office_boys/store-office-boy', [App\Http\Controllers\PreEmployeeController::class, 'storePreEmployees'])->name('office_boys.storePreEmployees');

    Route::get('/pre_employee/thank-you', [App\Http\Controllers\PreEmployeeController::class, 'thankYou'])->name('pre_employee.thank-you');

    //Developer Testing
    Route::get('/developer/test', [App\Http\Controllers\DeveloperController::class, 'sendDailyAbsentsEmail'])->name('developer.test');
    Route::get('/developer/email', [App\Http\Controllers\DeveloperController::class, 'birthday'])->name('developer.email');

    Route::get('/', function () {
        return redirect()->route('admin.login');
    });

    Route::get('admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
    Route::post('admin/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('admin/otp-verification', [AdminController::class, 'otpVerification'])->name('admin.otpVerification');
    Route::get('admin/otp', [AdminController::class, 'otpForm'])->name('admin.otpForm');
    Route::get('/user/wfh_check_in', [AdminController::class, 'wfhCheckIn'])->name('user.wfh_check_in');
    Route::get('admin/otp', [AdminController::class, 'otpForm'])->name('admin.otpForm');
    Route::get('admin/get-key-name', [AdminController::class, 'getKeyName'])->name('admin.getKeyName');

    Route::middleware(['auth'])->group(function () {

        // Route::middleware(['valid_ip_addresses'])->group(function () {

        Route::get('verify-user-token', [AdminController::class, 'verifyUserToken'])->name('admin.verifyUserToken');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/update/{id}', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('/logout', [AdminController::class, 'logOut'])->name('user.logout');
        Route::get('/wfh_checkout', [AdminController::class, 'wfhCheckOut'])->name('user.wfh_checkout');

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/departments', [AdminController::class, 'departments'])->name('departments');
        Route::get('/calendar', [AdminController::class, 'calendar'])->name('calendar');

        Route::get('/designations/trashed', [App\Http\Controllers\Admin\DesignationController::class, 'trashed'])->name('designations.trashed');
        Route::get('/designations/restore/{id}', [App\Http\Controllers\Admin\DesignationController::class, 'restore'])->name('designations.restore');

        Route::get('/employment_status/trashed', [App\Http\Controllers\Admin\EmploymentStatusController::class, 'trashed'])->name('employment_status.trashed');
        Route::get('/employment_status/restore/{id}', [App\Http\Controllers\Admin\EmploymentStatusController::class, 'restore'])->name('employment_status.restore');

        Route::get('/positions/trashed', [App\Http\Controllers\Admin\PositionController::class, 'trashed'])->name('positions.trashed');
        Route::get('/positions/restore/{id}', [App\Http\Controllers\Admin\PositionController::class, 'restore'])->name('positions.restore');

        Route::get('/work_shifts/trashed', [App\Http\Controllers\Admin\WorkShiftController::class, 'trashed'])->name('work_shifts.trashed');
        Route::get('/work_shifts/restore/{id}', [App\Http\Controllers\Admin\WorkShiftController::class, 'restore'])->name('work_shifts.restore');
        Route::get('/get_work_shifts', [App\Http\Controllers\Admin\WorkShiftController::class, 'getWorkShifts'])->name('get_work_shifts');

        Route::get('/departments/trashed', [App\Http\Controllers\Admin\DepartmentController::class, 'trashed'])->name('departments.trashed');
        Route::get('/departments/restore/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'restore'])->name('departments.restore');
        Route::get('/departments/status/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'status'])->name('departments.status');
        Route::get('/departments/add-manager/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'addManager'])->name('departments.add-manager');
        Route::get('/departments/add-shift/{id}', [App\Http\Controllers\Admin\DepartmentController::class, 'addShift'])->name('departments.add-shift');
        Route::get('/departments/employees', [App\Http\Controllers\Admin\DepartmentController::class, 'getEmployees'])->name('departments.employees');

        Route::get('/bank_accounts/status/{id}', [App\Http\Controllers\Admin\BankAccountController::class, 'status'])->name('bank_accounts.status');
        Route::get('/bank_accounts/export/excel', [App\Http\Controllers\Admin\ExportController::class, 'bankAccountsExportExcel'])->name('bank_accounts.export.excel');
        Route::get('/bank_accounts/export/pdf', [App\Http\Controllers\Admin\ExportController::class, 'bankAccountsExportPdf'])->name('bank_accounts.export.pdf');

        Route::get('/employees/trashed', [App\Http\Controllers\Admin\EmployeeController::class, 'trashed'])->name('employees.trashed');
        Route::get('/employees/restore/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'restore'])->name('employees.restore');
        Route::post('/employees/status/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'status'])->name('employees.status');
        Route::put('/employees/re-hire', [App\Http\Controllers\Admin\EmployeeController::class, 'reHire'])->name('employees.re-hire');
        Route::get('/employees/get_user_details', [App\Http\Controllers\Admin\EmployeeController::class, 'getUserDetails'])->name('employees.get_user_details');
        Route::get('/get_promote_data', [App\Http\Controllers\Admin\EmployeeController::class, 'getPromoteData'])->name('get_promote_data');
        Route::post('/store_promote', [App\Http\Controllers\Admin\EmployeeController::class, 'promote'])->name('store_promote');
        Route::post('/store_work_shift', [App\Http\Controllers\Admin\EmployeeController::class, 'storeWorkShift'])->name('store_work_shift');
        Route::get('/employee-permanent/{user_id}', [App\Http\Controllers\Admin\EmployeeController::class, 'employeePermanent'])->name('employee-permanent');

        Route::get('/employees/salary_details/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\Admin\EmployeeController::class, 'salaryDetails'])->name('employees.salary_details');
        Route::get('/employees/generate_salary_slip/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\Admin\EmployeeController::class, 'generateSalarySlip'])->name('employees.generate_salary_slip');

        Route::get('/employees/get-team-members/{id?}', [App\Http\Controllers\Admin\EmployeeController::class, 'getTeamMembers'])->name('employees.get-team-members');
        Route::get('/manager/teams-members', [App\Http\Controllers\Admin\EmployeeController::class, 'managerTeamMembers'])->name('manager.team-members');
        Route::get('/employees/teams-members', [App\Http\Controllers\Admin\EmployeeController::class, 'teamMembers'])->name('employees.team-members');
        Route::get('/employees/get-team-summary/{id}', [App\Http\Controllers\Admin\EmployeeController::class, 'teamSummary'])->name('employees.get-team-summary');

        Route::get('/user-direct/permission/edit/{slug}', [App\Http\Controllers\Admin\EmployeeController::class, 'userDirectPermissionEdit'])->name('user-direct.permission.edit');
        Route::put('/user-direct/permission/update/{slug}', [App\Http\Controllers\Admin\EmployeeController::class, 'userDirectPermissionUpdate'])->name('user-direct.permission.update');

        // currency converter route
        Route::get("get-currency-rate", [EmployeeController::class, 'getCurrencyRate'])->name("employees.getCurrencyRate");
        Route::get("employees_for_it", [EmployeeController::class, 'employeesForIt'])->name("employees_for_it");
        // currency converter route

        Route::get('/announcements/trashed', [App\Http\Controllers\Admin\AnnouncementController::class, 'trashed'])->name('announcements.trashed');
        Route::get('/announcements/restore/{id}', [App\Http\Controllers\Admin\AnnouncementController::class, 'restore'])->name('announcements.restore');

        Route::get('/authorize_emails/trashed', [App\Http\Controllers\Admin\AuthorizeEmailController::class, 'trashed'])->name('authorize_emails.trashed');
        Route::get('/authorize_emails/restore/{id}', [App\Http\Controllers\Admin\AuthorizeEmailController::class, 'restore'])->name('authorize_emails.restore');

        Route::get('/leave_types/trashed', [App\Http\Controllers\Admin\LeaveTypeController::class, 'trashed'])->name('leave_types.trashed');
        Route::get('/leave_types/restore/{id}', [App\Http\Controllers\Admin\LeaveTypeController::class, 'restore'])->name('leave_types.restore');

        Route::get('/letter_templates/trashed', [App\Http\Controllers\Admin\LetterTemplateController::class, 'trashed'])->name('letter_templates.trashed');
        Route::get('/letter_templates/restore/{id}', [App\Http\Controllers\Admin\LetterTemplateController::class, 'restore'])->name('letter_templates.restore');

        Route::get('/employee_letters/trashed', [App\Http\Controllers\Admin\EmployeeLetterController::class, 'trashed'])->name('employee_letters.trashed');
        Route::get('/employee_letters/restore/{id}', [App\Http\Controllers\Admin\EmployeeLetterController::class, 'restore'])->name('employee_letters.restore');
        Route::get('/employee_letters/download/{id}', [App\Http\Controllers\Admin\EmployeeLetterController::class, 'downloadLetter'])->name('employee_letters.download');
        Route::get('/employee_letters/all_letters', [App\Http\Controllers\Admin\EmployeeLetterController::class, 'allEmployeeLetters'])->name('employee_letters.all_letters');

        Route::get('/profile_cover_images/trashed', [App\Http\Controllers\Admin\ProfileCoverImageController::class, 'trashed'])->name('profile_cover_images.trashed');
        Route::get('/profile_cover_images/restore/{id}', [App\Http\Controllers\Admin\ProfileCoverImageController::class, 'restore'])->name('profile_cover_images.restore');
        Route::get('/profile_cover_images/status/{id}', [App\Http\Controllers\Admin\ProfileCoverImageController::class, 'status'])->name('profile_cover_images.status');

        Route::get('/user_leaves/report/{getUser?}', [App\Http\Controllers\UserLeaveController::class, 'leaveReport'])->name('user_leaves.report');
        Route::get('/employee/leaves/report/{getUser?}', [App\Http\Controllers\UserLeaveController::class, 'employeeLeaveReport'])->name('employee.leaves.report');
        Route::get('/user_leaves/show/{id}', [App\Http\Controllers\UserLeaveController::class, 'show'])->name('user_leaves.show');
        Route::get('/user_leaves/status/{id}', [App\Http\Controllers\UserLeaveController::class, 'status'])->name('user_leaves.status');
        Route::get('/team/user_leaves/status/{status}', [App\Http\Controllers\UserLeaveController::class, 'blukStatus'])->name('team.user_leaves.status');

        Route::get('/user/attendance/summary/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'summary'])->name('user.attendance.summary');
        Route::get('/employee/attendance/summary/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'employeeSummary'])->name('employee.attendance.summary');
        Route::get('/user/attendance/terminated_employee_summary/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'terminatedEmployeeSummary'])->name('user.attendance.terminated_employee_summary');
        Route::get('/employee/attendance/advance-filter/summary', [App\Http\Controllers\AttendanceController::class, 'employteeAdvanceFilterSummary'])->name('employee.attendance.advance-filter.summary');
        Route::get('/user/attendance/advance-filter/summary', [App\Http\Controllers\AttendanceController::class, 'advanceFilterSummary'])->name('user.attendance.advance-filter.summary');
        Route::get('/user/discrepancies/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'discrepancies'])->name('user.discrepancies');
        Route::get('/team/discrepancies/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'teamDiscrepancies'])->name('team.discrepancies');
        Route::get('/manager/team/discrepancies/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'managerTeamDiscrepancies'])->name('manager.team.discrepancies');
        Route::get('/user/discrepancy/show/{id}', [App\Http\Controllers\AttendanceController::class, 'showDiscrepancy'])->name('user.discrepancy.show');
        Route::get('/user/attendance/daily-log/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'dailyLog'])->name('user.attendance.daily-log');
        Route::get('/employee/attendance/daily-log/{getMonth?}/{getYear?}/{getUser?}', [App\Http\Controllers\AttendanceController::class, 'employeeDailyLog'])->name('employee.attendance.daily-log');
        Route::get('/user/discrepancy/status/{id?}/{status?}', [App\Http\Controllers\AttendanceController::class, 'ApproveOrRejectDiscrepancy'])->name('user.discrepancy.status');
        Route::get('/team/discrepancy/status/{status?}', [App\Http\Controllers\AttendanceController::class, 'ApproveOrRejectTeamDiscrepancies'])->name('team.discrepancy.status');

        Route::get('/employee/monthly/attendance/report/{getMonth?}/{getYear?}', [App\Http\Controllers\AttendanceController::class, 'monthlyAttendanceReport'])->name('employee.monthly.attendance.report');
        Route::get('/employee/attendance/monthly/report/filter', [App\Http\Controllers\AttendanceController::class, 'monthlyAttendanceReportFilter'])->name('employee.attendance.monthly.report.filter');
        Route::get('/employee/attendance/monthly/report/export', [App\Http\Controllers\AttendanceController::class, 'monthlyAttendanceReportExport'])->name('employee.attendance.monthly.report.export');
        Route::get('/employee/attendance/monthly/list', [App\Http\Controllers\AttendanceController::class, 'attendanceList'])->name('employee.attendance.monthly.attendanceList');


        Route::get('/team/attendance/get-discrepancies', [App\Http\Controllers\AttendanceController::class, 'getDiscrepancies'])->name('team.attendance.get-discrepancies');
        Route::get('/team/attendance/get-leaves', [App\Http\Controllers\AttendanceController::class, 'getLeaves'])->name('team.attendance.get-leaves');

        Route::get('/team/leave-requests', [App\Http\Controllers\Admin\TeamController::class, 'leaveRequests'])->name('team.leave-requests');
        Route::get('/team/leave-reports', [App\Http\Controllers\Admin\TeamController::class, 'leaveReports'])->name('team.leave-reports');

        Route::get('/manager/team/leaves/{getUser?}', [App\Http\Controllers\UserLeaveController::class, 'managerTeamLeaves'])->name('manager.team.leaves');
        Route::get('/team/leaves/{getUser?}', [App\Http\Controllers\UserLeaveController::class, 'teamLeaves'])->name('team.leaves');
        Route::get('members-of-department', [App\Http\Controllers\UserLeaveController::class, 'leavesGetMembersOfDepartment'])->name('team.leavesGetMembersOfDepartment');
        Route::get('/user/leaves/status/{id}/{status}', [App\Http\Controllers\UserLeaveController::class, 'ApproveOrRejectLeave'])->name('user.leaves.status');

        Route::get('/notifications/mark-as-read', [App\Http\Controllers\Admin\NotificationController::class, 'notificationMarkAsRead'])->name('notifications.mark-as-read');
        Route::get('/notifications/delete', [App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.delete');

        Route::get('/pre_employees/convert-pdf/{id}', [App\Http\Controllers\PreEmployeeController::class, 'convertPdf'])->name('pre_employees.convert-pdf');
        Route::get('/pre_employees/trashed', [App\Http\Controllers\PreEmployeeController::class, 'trashed'])->name('pre_employees.trashed');
        Route::get('/pre_employees/restore/{id}', [App\Http\Controllers\PreEmployeeController::class, 'restore'])->name('pre_employees.restore');

        Route::get('/tickets/trashed', [App\Http\Controllers\TicketController::class, 'trashed'])->name('tickets.trashed');
        Route::get('/tickets/restore/{id}', [App\Http\Controllers\TicketController::class, 'restore'])->name('tickets.restore');
        Route::get('/tickets/status/{id}', [App\Http\Controllers\TicketController::class, 'status'])->name('tickets.status');
        Route::get('/tickets/all_tickets', [App\Http\Controllers\TicketController::class, 'allTickets'])->name('tickets.all_tickets');
        Route::get('/admin/tickets/all_tickets', [App\Http\Controllers\TicketController::class, 'adminAllTickets'])->name('admin.tickets.all_tickets');
        Route::get('/tickets/waiting_approval_tickets', [App\Http\Controllers\TicketController::class, 'waitingApprovalTickets'])->name('tickets.waiting_approval_tickets');

        //Vehicles
        Route::post('/vehicles/update_vehicle', [App\Http\Controllers\Admin\VehicleController::class, 'update'])->name('vehicles.update_vehicle');
        Route::get('/vehicles/status/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'status'])->name('vehicles.status');
        Route::get('/vehicles/remove-image/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'removeImage'])->name('vehicles.remove-image');
        Route::get('/vehicles/trashed', [App\Http\Controllers\Admin\VehicleController::class, 'trashed'])->name('vehicles.trashed');
        Route::get('/vehicles/restore/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'restore'])->name('vehicles.restore');
        Route::get('/vehicles/inspections/history/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'inspectionsHistory'])->name('vehicles.inspections.history');
        Route::get('/vehicles/users/history/{id}', [App\Http\Controllers\Admin\VehicleController::class, 'usersHistory'])->name('vehicles.users.history');

        Route::post('/tickets/update_ticket', [App\Http\Controllers\TicketController::class, 'update'])->name('tickets.update_ticket');
        Route::get('/team/tickets/{getUser?}', [App\Http\Controllers\TicketController::class, 'teamTickets'])->name('team.tickets');
        Route::get('/admin/team/tickets/{getUser?}', [App\Http\Controllers\TicketController::class, 'AdminTeamTickets'])->name('admin.team.tickets');
        Route::get('/all/tickets/{getUser?}', [App\Http\Controllers\TicketController::class, 'index'])->name('all.tickets');

        Route::get('/vehicle_owners/status/{id}', [App\Http\Controllers\Admin\VehicleOwnerController::class, 'status'])->name('vehicle_owners.status');
        Route::get('/vehicle_owners/vehicles/{id}', [App\Http\Controllers\Admin\VehicleOwnerController::class, 'vehicles'])->name('vehicle_owners.vehicles');
        Route::get('/vehicle_owners/trashed', [App\Http\Controllers\Admin\VehicleOwnerController::class, 'trashed'])->name('vehicle_owners.trashed');
        Route::get('/vehicle_owners/restore/{id}', [App\Http\Controllers\Admin\VehicleOwnerController::class, 'restore'])->name('vehicle_owners.restore');

        Route::get('/vehicle_users/status/{id}', [App\Http\Controllers\Admin\VehicleUserController::class, 'status'])->name('vehicle_users.status');
        Route::get('/vehicle_users/trashed', [App\Http\Controllers\Admin\VehicleUserController::class, 'trashed'])->name('vehicle_users.trashed');
        Route::get('/vehicle_users/restore/{id}', [App\Http\Controllers\Admin\VehicleUserController::class, 'restore'])->name('vehicle_users.restore');
        Route::POST('/vehicle_users/share_vehicle', [App\Http\Controllers\Admin\VehicleUserController::class, 'share'])->name('vehicle_users.share_vehicle');
        Route::get('/vehicle_users/vehicle_inspection/history/{vehicle_id}/{user_id}', [App\Http\Controllers\Admin\VehicleUserController::class, 'inspectionHistory'])->name('vehicle_users.vehicle_inspection.history');
        Route::get('/vehicle_users/all_users', [App\Http\Controllers\Admin\VehicleUserController::class, 'allVehicleUsers'])->name('vehicle_users.all_users');

        Route::get('/vehicle_allowances/status/{id}', [App\Http\Controllers\Admin\VehicleAllowanceController::class, 'status'])->name('vehicle_allowances.status');
        Route::get('/vehicle_allowances/trashed', [App\Http\Controllers\Admin\VehicleAllowanceController::class, 'trashed'])->name('vehicle_allowances.trashed');
        Route::get('/vehicle_allowances/restore/{id}', [App\Http\Controllers\Admin\VehicleAllowanceController::class, 'restore'])->name('vehicle_allowances.restore');

        Route::get('/vehicle_rents/rent/histort/{vehicle_id}', [App\Http\Controllers\Admin\VehicleRentController::class, 'rentHistory'])->name('vehicle_rents.rent.histort');
        Route::get('/vehicle_rents/status/{id}', [App\Http\Controllers\Admin\VehicleRentController::class, 'status'])->name('vehicle_rents.status');
        Route::get('/vehicle_rents/trashed', [App\Http\Controllers\Admin\VehicleRentController::class, 'trashed'])->name('vehicle_rents.trashed');
        Route::get('/vehicle_rents/restore/{id}', [App\Http\Controllers\Admin\VehicleRentController::class, 'restore'])->name('vehicle_rents.restore');

        Route::get('/vehicle_inspections/status/{id}', [App\Http\Controllers\Admin\VehicleInspectionController::class, 'status'])->name('vehicle_inspections.status');
        Route::get('/vehicle_inspections/trashed', [App\Http\Controllers\Admin\VehicleInspectionController::class, 'trashed'])->name('vehicle_inspections.trashed');
        Route::get('/vehicle_inspections/restore/{id}', [App\Http\Controllers\Admin\VehicleInspectionController::class, 'restore'])->name('vehicle_inspections.restore');
        //Vehicles

        Route::get('/insurances/trashed', [App\Http\Controllers\InsuranceController::class, 'trashed'])->name('insurances.trashed');
        Route::get('/insurances/restore/{id}', [App\Http\Controllers\InsuranceController::class, 'restore'])->name('insurances.restore');
        Route::get('/mark_attendance/{getUser}', [App\Http\Controllers\Admin\AttendanceAdjustmentController::class, 'index']);

        Route::post('/documents/update_document', [App\Http\Controllers\DocumentController::class, 'update'])->name('documents.update_document');
        Route::post('/document_attachment/destroy/{id}', [App\Http\Controllers\DocumentController::class, 'documentAttachmentDestroy'])->name('document_attachment.destroy');
        Route::post('/document_attachment/update/{id}', [App\Http\Controllers\DocumentController::class, 'documentAttachmentUpdate'])->name('document_attachment.update');
        Route::get('/documents/trashed', [App\Http\Controllers\DocumentController::class, 'trashed'])->name('documents.trashed');
        Route::get('/documents/restore/{id}', [App\Http\Controllers\DocumentController::class, 'restore'])->name('documents.restore');
        Route::get('/documents/download-single/{id}', [App\Http\Controllers\DocumentController::class, 'downloadSingle'])->name('documents.downloadSingle');

        Route::get('/resignations/status/{id}', [App\Http\Controllers\Admin\ResignationController::class, 'status'])->name('resignations.status');
        Route::get('/resignations/trashed', [App\Http\Controllers\Admin\ResignationController::class, 'trashed'])->name('resignations.trashed');
        Route::get('/resignations/restore/{id}', [App\Http\Controllers\Admin\ResignationController::class, 'restore'])->name('resignations.restore');
        Route::get('/resignations/employee_resignations', [App\Http\Controllers\Admin\ResignationController::class, 'employeeResignations'])->name('resignations.employee_resignations');
        Route::get('/resignations/team_resignations', [App\Http\Controllers\Admin\ResignationController::class, 'teamResignations'])->name('resignations.team_resignations');
        Route::get('/resignations/re-hired/employees', [App\Http\Controllers\Admin\ResignationController::class, 'reHiredEmployees'])->name('resignations.re-hired.employees');
        Route::get('/resignations/re-hired/employees/history', [App\Http\Controllers\Admin\ResignationController::class, 'hiringHistory'])->name('resignations.hiringHistory');
        Route::get('/admin/resignations/re-hired/employees', [App\Http\Controllers\Admin\ResignationController::class, 'adminReHiredEmployees'])->name('admin.resignations.re-hired.employees');

        Route::get('/insurance/export/excel', [App\Http\Controllers\Admin\ExportController::class, 'exportExcel'])->name('insurance.export.excel');
        Route::get('/insurance/export/pdf', [App\Http\Controllers\Admin\ExportController::class, 'exportPdf'])->name('insurance.export.pdf');

        Route::get('/wfh_employees/status/{id}', [App\Http\Controllers\Admin\WFHEmployeeController::class, 'status'])->name('wfh_employees.status');
        Route::get('/monthly_salary_reports/monthly_report/{getMonth?}/{getYear?}', [App\Http\Controllers\MonthlySalaryReportController::class, 'index'])->name('monthly_salary_reports.monthly_report');
        Route::get('/monthly_salary_reports/export_monthly_salary_report/download/{getMonth?}/{getYear?}/{department?}', [App\Http\Controllers\MonthlySalaryReportController::class, 'monthlySalaryReportDownload'])->name('monthly_salary_reports.export_monthly_salary_report.download');

        // Stationary Categories Routes
        Route::get('/stationary_categories/trashed', [App\Http\Controllers\Admin\StationaryCategoryController::class, 'trashed'])->name('stationary_categories.trashed');
        Route::get('/stationary_categories/restore/{id}', [App\Http\Controllers\Admin\StationaryCategoryController::class, 'restore'])->name('stationary_categories.restore');

        // Stationary Routes
        Route::get('/stationary/trashed', [App\Http\Controllers\Admin\StationaryController::class, 'trashed'])->name('stationary.trashed');
        Route::get('/stationary/restore/{id}', [App\Http\Controllers\Admin\StationaryController::class, 'restore'])->name('stationary.restore');
        Route::get('/stationary/list/{category_id}', [App\Http\Controllers\Admin\StationaryController::class, 'categoryStationary'])->name('stationary.list');


        // user documents module
        Route::get('get-user-documents', [ProfileController::class, 'getUserDocuments'])->name('profile.getUserDocuments');
        Route::post('store-document-as-user', [ProfileController::class, 'storeDocumentAsUser'])->name('profile.storeDocumentAsUser');
        Route::get('view-documents/{id}', [ProfileController::class, 'viewDocuments'])->name('profile.viewDocuments');
        Route::get('edit-documents/{id}', [ProfileController::class, 'editDocuments'])->name('profile.editDocuments');
        Route::post('update-documents/{id}', [ProfileController::class, 'updateDocuments'])->name('profile.updateDocuments');
        Route::get('delete-documents-user/{id}', [ProfileController::class, 'deleteDocuments'])->name('profile.deleteDocuments');
        Route::get('delete-documents-with-attachments-user/{id}', [ProfileController::class, 'deleteDocumentWithAttachments'])->name('profile.deleteDocumentWithAttachments');
        // user documents module
        Route::get("my-assets", [ProfileController::class, 'myAssets'])->name("profile.myAssets");


        Route::get('delete-documents-hr/{id}', [DocumentController::class, 'deleteDocuments'])->name('documents.deleteDocumentsHr');
        Route::get('delete-documents-with-attachments-hr/{id}', [DocumentController::class, 'deleteDocumentWithAttachments'])->name('documents.deleteDocumentWithAttachmentsHr');


        // Inventory
        Route::get("inventory-category/trashed", [InventoryCategoryController::class, 'trashed'])->name("inventory-category.trashed");
        Route::get('inventory-category/restore/{id}', [InventoryCategoryController::class, 'restore'])->name('inventory-category.restore');
        Route::get("add-more/{id}", [AssetController::class, 'addMore'])->name("assets.addMore");
        Route::post("add-more-update/{id}", [AssetController::class, 'addMoreUpdate'])->name("assets.addMoreUpdate");
        Route::get("assets/trashed", [AssetController::class, 'trashed'])->name("assets.trashed");
        Route::get('assets/restore/{id}', [AssetController::class, 'restore'])->name('assets.restore');
        Route::get("assign-to-employee/{id}", [AssetController::class, 'assign'])->name("assets.assign");
        Route::post("assign-to-employee-update/{id}", [AssetController::class, 'assignUpdate'])->name("assets.assignUpdate");
        Route::get("assigned-assets", [AssetController::class, 'assignedList'])->name("assets.assignedList");
        Route::get("delete-assigned-assets/{id}", [AssetController::class, 'removeAssignee'])->name("assets.removeAssignee");
        Route::get("get-childs-of-asset/{id}", [AssetController::class, 'getChildAsset'])->name("assets.getChildAsset");
        Route::get("unassign-modal-data", [AssetController::class, 'unassignModal'])->name("assets.unassignModal");
        Route::post("unassign-modal-data-update", [AssetController::class, 'unassignedFromEmployee'])->name("assets.unassignedFromEmployee");
        Route::get("view-assignee-logs-of-asset/{id}", [AssetController::class, 'assetAssigneeLogs'])->name("assets.assetAssigneeLogs");
        Route::get("logs-list-of-asset/{id}", [AssetController::class, 'assetAssigneeLogsList'])->name("assets.assetAssigneeLogsList");
        Route::get("asset-user-timeline/{id}", [AssetController::class, 'assetUsertimeline'])->name("assets.assetUsertimeline");
        Route::post("mark-as-damage", [AssetController::class, 'markAsDamage'])->name("assets.markAsDamage");
        // Inventory

        Route::get('/ip-managements/trashed', [IpManagementController::class, 'trashed'])->name('ip-managements.trashed');
        Route::get('/ip-managements/restore/{id}', [IpManagementController::class, 'restore'])->name('ip-managements.restore');
        // ip management

        // grievances
        Route::get("/grievances/trashed", [GrievanceController::class, 'trashed'])->name("grievances.trashed");
        Route::get("/grievances/restore/{id}", [GrievanceController::class, 'restore'])->name("grievances.restore");
        Route::get("/grievances/my-grievance", [GrievanceController::class, 'myGrievance'])->name("grievances.myGrievance");

        // user side
        Route::get("/grievances/my-trashed", [GrievanceController::class, 'myTrashed'])->name("grievances.myTrashed");
        // user side
        // grievances



        Route::post("import-bank-accounts", [BankAccountController::class, 'import'])->name("bank_accounts.import");


        // Stationary Categories Routes
        Route::get('/stationary_categories/trashed', [App\Http\Controllers\Admin\StationaryCategoryController::class, 'trashed'])->name('stationary_categories.trashed');
        Route::get('/stationary_categories/restore/{id}', [App\Http\Controllers\Admin\StationaryCategoryController::class, 'restore'])->name('stationary_categories.restore');

        //holidays
        Route::get('/holidays/trashed', [App\Http\Controllers\Admin\HolidayController::class, 'trashed'])->name('holidays.trashed');
        Route::get('/holidays/restore/{id}', [App\Http\Controllers\Admin\HolidayController::class, 'restore'])->name('holidays.restore');

        // Stationary Routes
        Route::get('/stationary/trashed', [App\Http\Controllers\Admin\StationaryController::class, 'trashed'])->name('stationary.trashed');
        Route::get('/stationary/restore/{id}', [App\Http\Controllers\Admin\StationaryController::class, 'restore'])->name('stationary.restore');
        Route::get('/stationary/list/{category_id}', [App\Http\Controllers\Admin\StationaryController::class, 'categoryStationary'])->name('stationary.list');

        // assets routes
        Route::get("assets/chart-data", [AssetController::class, "chartData"])->name("assets.chartData");
        Route::get("get-employees-for-roles-page", [RoleController::class, "getRoleEmployees"])->name("roles.getRoleEmployees");

        // second pre employees routes
        // Route::get('/pre_employees/create-form', [App\Http\Controllers\PreEmployeeController::class, 'createForm'])->name('pre_employees.createForm');
        // Route::post('/pre_employees/store-pre-employees', [App\Http\Controllers\PreEmployeeController::class, 'storePreEmployees'])->name('pre_employees.storePreEmployees');

        // log routes
        Route::get('/logs/{model}/{id}/{title}', [LogController::class, 'showJsonData'])->name('logs.showJsonData');
        Route::get('/logs/view-documents/{id}', [LogController::class, 'viewDocuments'])->name('logs.viewDocuments');

        // office boys routes
        Route::group(['prefix' => 'pre_employees'], function () {
            Route::get('/office_boys', [App\Http\Controllers\PreEmployeeController::class, 'officeBoy'])->name('office_boys.index');
            Route::get('/office_boys/trashed', [App\Http\Controllers\PreEmployeeController::class, 'trashedOfficeBoy'])->name('office_boys.trashed');
            Route::delete('/office_boys/{id}', [App\Http\Controllers\PreEmployeeController::class, 'destroy'])->name('office_boys.destroy');
            Route::get('/office_boys/convert-pdf/{id}', [App\Http\Controllers\PreEmployeeController::class, 'convertPdf'])->name('office_boys.convert-pdf');
            Route::get('/office_boys/restore/{id}', [App\Http\Controllers\PreEmployeeController::class, 'restore'])->name('office_boys.restore');
            Route::get('/office_boys/show/{id}', [App\Http\Controllers\PreEmployeeController::class, 'show'])->name('office_boys.show');
            Route::get('/office_boys/edit/{id}', [App\Http\Controllers\PreEmployeeController::class, 'edit'])->name('office_boys.edit');
            Route::patch('/office_boys/update/{id}', [App\Http\Controllers\PreEmployeeController::class, 'update'])->name('office_boys.update');
            Route::get('/office_boys/get-managers', [App\Http\Controllers\PreEmployeeController::class, 'getManagers'])->name('office_boys.getManagers');
            Route::get('/office_boys/update-manager', [App\Http\Controllers\PreEmployeeController::class, 'updateManager'])->name('office_boys.updateManager');
        });
        // office boys routes


        Route::resource('/logs', LogController::class);

        // assets routes
        Route::get("assets/chart-data", [AssetController::class, "chartData"])->name("assets.chartData");
        Route::get("get-employees-for-roles-page", [RoleController::class, "getRoleEmployees"])->name("roles.getRoleEmployees");

        //bluck attendance adjustments
        Route::get('/mark_attendance/{getUser}', [App\Http\Controllers\Admin\AttendanceAdjustmentController::class, 'index'])->name('mark_attendence_redirect');
        Route::get('/get_mark_attendance_by_admin', [App\Http\Controllers\Admin\AttendanceAdjustmentController::class, 'getMarkAttendanceByAdmin'])->name('get.mark.attendance.by.admin');
        Route::post('/mark_attendance_by_admin', [App\Http\Controllers\Admin\AttendanceAdjustmentController::class, 'markAttendanceByAdmin'])->name('mark.attendance.by.admin');
        //bluck attendance adjustments

        //pre-employment
        Route::get('/select/managers/{id}', [App\Http\Controllers\PreEmployeeController::class, 'selectManagers'])->name('select.managers');
        Route::put('/assign_managers/{id}', [App\Http\Controllers\PreEmployeeController::class, 'assignManager'])->name('assign.manager');
        Route::get('/terminated_employees', [App\Http\Controllers\Admin\ResignationController::class, 'terminatedEmployees'])->name('terminated_employees');
        Route::get('/new_joinings', [App\Http\Controllers\Admin\EmployeeController::class, 'newJoinings'])->name('new_joinings');

        Route::resource('/roles', App\Http\Controllers\Admin\RoleController::class);
        Route::resource('/permissions', App\Http\Controllers\Admin\PermissionController::class);
        Route::resource('/designations', App\Http\Controllers\Admin\DesignationController::class);
        Route::resource('/positions', App\Http\Controllers\Admin\PositionController::class);
        Route::resource('/work_shifts', App\Http\Controllers\Admin\WorkShiftController::class);
        Route::resource('/departments', App\Http\Controllers\Admin\DepartmentController::class);
        Route::resource('/announcements', App\Http\Controllers\Admin\AnnouncementController::class);
        Route::resource('/employment_status', App\Http\Controllers\Admin\EmploymentStatusController::class);
        Route::resource('/employees', App\Http\Controllers\Admin\EmployeeController::class);
        Route::resource('/profile_cover_images', App\Http\Controllers\Admin\ProfileCoverImageController::class);
        Route::resource('/bank_accounts', App\Http\Controllers\Admin\BankAccountController::class);
        Route::resource('/user_contacts', App\Http\Controllers\Admin\UserContactController::class);
        Route::resource('/settings', App\Http\Controllers\Admin\SettingController::class);
        Route::resource('/leave_types', App\Http\Controllers\Admin\LeaveTypeController::class);
        Route::resource('/user_leaves', App\Http\Controllers\UserLeaveController::class);
        Route::resource('/chat', App\Http\Controllers\ChatController::class);
        Route::resource('/authorize_emails', App\Http\Controllers\Admin\AuthorizeEmailController::class);
        Route::resource('/letter_templates', App\Http\Controllers\Admin\LetterTemplateController::class);
        Route::resource('/employee_letters', App\Http\Controllers\Admin\EmployeeLetterController::class);
        Route::resource('/notifications', App\Http\Controllers\Admin\NotificationController::class);
        Route::resource('/pre_employees', App\Http\Controllers\PreEmployeeController::class);
        Route::resource('/tickets', App\Http\Controllers\TicketController::class);
        Route::resource('/vehicles', App\Http\Controllers\Admin\VehicleController::class);
        Route::resource('/vehicle_owners', App\Http\Controllers\Admin\VehicleOwnerController::class);
        Route::resource('/vehicle_inspections', App\Http\Controllers\Admin\VehicleInspectionController::class);
        Route::resource('/vehicle_users', App\Http\Controllers\Admin\VehicleUserController::class);
        Route::resource('/vehicle_allowances', App\Http\Controllers\Admin\VehicleAllowanceController::class);
        Route::resource('/vehicle_rents', App\Http\Controllers\Admin\VehicleRentController::class);
        Route::resource('/insurances', App\Http\Controllers\InsuranceController::class);
        Route::resource('/mark_attendance', App\Http\Controllers\Admin\AttendanceAdjustmentController::class);
        Route::resource('/resignations', App\Http\Controllers\Admin\ResignationController::class);
        Route::resource('/wfh_employees', App\Http\Controllers\Admin\WFHEmployeeController::class);
        Route::resource('/documents', App\Http\Controllers\DocumentController::class);
        Route::resource('/monthly_salary_reports', App\Http\Controllers\MonthlySalaryReportController::class);
        Route::resource('/stationary_categories', App\Http\Controllers\Admin\StationaryCategoryController::class);
        Route::resource('/stationary', App\Http\Controllers\Admin\StationaryController::class);
        Route::resource('inventory-category', InventoryCategoryController::class);
        Route::resource('assets', AssetController::class);
        Route::resource('/grievances', GrievanceController::class);
        Route::resource('/ip-managements', IpManagementController::class);
        Route::resource('/logs', LogController::class);
    Route::resource('/holidays', App\Http\Controllers\Admin\HolidayController::class);
    // });
});

Route::impersonate();
require __DIR__ . '/auth.php';
