<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index(Request $request){
        $title = 'All Notifications';

        Auth::user()->notifications->markAsRead();
        $model = auth()->user()->notifications;

        // $model = [];
        // Notification::where('user_id', auth()->user()->id)
        //     ->latest()
        //     ->chunk(100, function ($notifications) use (&$model) {
        //         foreach ($notifications as $notification) {
        //             $model[] = $notification;
        //         }
        // });

        if($request->ajax() && $request->loaddata == "yes") {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('select', function ($model) {
                    return '<input class="form-check-input checkbox" type="checkbox" value="'.$model->id.'" id="checkbox">';
                })
                ->addColumn('title', function($model){
                    return '<span class="text-capitalize text-primary">'.$model->data['title'].'</span>';
                })
                ->editColumn('created_at', function ($model) {
                    return Carbon::parse($model->created_at)->format('d, M Y');
                })
                ->editColumn('notifiable_id', function ($model) {
                    return view('admin.notifications.employee-profile', ['notification' => $model])->render();
                })
                ->addColumn('action', function($model){
                    return view('admin.notifications.action', ['model' => $model])->render();
                })
                ->rawColumns(['notifiable_id', 'action','title', 'select'])
                ->make(true);
        }

        return view('admin.notifications.index', compact('title'));
    }

    public function show($id){
        $model = Notification::where('id', $id)->first();
        $data = json_decode($model->data);

        return (string) view('admin.notifications.show_content', compact('model', 'data'));
    }

    public function notificationMarkAsRead(){
        Auth::user()->notifications->markAsRead();
        return count(auth()->user()->unreadnotifications);
    }

    public function destroy(Request $request){
        $notificationIds = json_decode($request->data);

        foreach($notificationIds as $notify){
            Notification::where('id', $notify->id)->delete();
        }

        return 'true';
    }
}
