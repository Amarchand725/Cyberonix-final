<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Str;

class SettingController extends Controller
{
    public function create()
    {
        $this->authorize('setting-create');
        $settings = Setting::first();
        if(empty($settings)){
            $title = 'Setting';
            return view('admin.settings.create', compact('title'));
        }else{
            return redirect()->route('settings.edit', $settings->id);
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'phone_number' => 'required',
            'email' => 'required',
            'address' => 'required',
            'max_leaves' => 'required|max:255',
            'max_discrepancies' => 'required|max:255',
            'insurance_eligibility' => 'required|max:255',
            'logo' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'black_logo' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'favicon' => 'required|image|mimes:jpeg,png',
            'slip_stamp' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'admin_signature' => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        DB::beginTransaction();

        try{
            $model = new Setting();

            if ($request->hasFile('logo')) {
                $logo = $request->file('logo');
                $logoName = rand() . '.' . $logo->getClientOriginalExtension();
                $logo->move(public_path('admin/assets/img/logo'), $logoName);

                $model->logo = $logoName;
            }

            if ($request->hasFile('black_logo')) {
                $black_logo = $request->file('black_logo');
                $logoName = rand() . '.' . $black_logo->getClientOriginalExtension();
                $black_logo->move(public_path('admin/assets/img/logo'), $logoName);

                $model->black_logo = $logoName;
            }

            if ($request->hasFile('favicon')) {
                $favicon = $request->file('favicon');
                $faviconName = rand() . '.' . $favicon->getClientOriginalExtension();
                $favicon->move(public_path('admin/assets/img/favicon'), $faviconName);

                $model->favicon = $faviconName;
            }

            if ($request->hasFile('slip_stamp')) {
                $slip_stamp = $request->file('slip_stamp');
                $logoName = rand() . '.' . $slip_stamp->getClientOriginalExtension();
                $slip_stamp->move(public_path('admin/assets/img/logo'), $logoName);

                $model->slip_stamp = $logoName;
            }

            if ($request->hasFile('admin_signature')) {
                $admin_signature = $request->file('admin_signature');
                $logoName = rand() . '.' . $admin_signature->getClientOriginalExtension();
                $admin_signature->move(public_path('admin/assets/img/logo'), $logoName);

                $model->admin_signature = $logoName;
            }

            $model->name = $request->name;
            $model->phone_number = $request->phone_number;
            $model->email = $request->email;
            $model->website_url = $request->website_url;
            $model->language = $request->language;
            $model->max_leaves = $request->max_leaves;
            $model->max_discrepancies = $request->max_discrepancies;
            $model->currency_symbol = $request->currency_symbol;
            $model->insurance_eligibility = $request->insurance_eligibility;
            $model->country = $request->country;
            $model->area = $request->area;
            $model->city = $request->city;
            $model->state = $request->state;
            $model->zip_code = $request->zip_code;
            $model->address = $request->address;
            $model->facebook_link = $request->facebook_link;
            $model->instagram_link = $request->instagram_link;
            $model->linked_in_link = $request->linked_in_link;
            $model->twitter_link = $request->twitter_link;
            $model->save();

            DB::commit();

            \LogActivity::addToLog('Setting details updated');

            return redirect()->route('settings.edit', $model->id)->with('message', 'Setting details inserted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $this->authorize('setting-edit');
        $title = 'Setting Details';
        $model = Setting::where('id', $id)->first();
        return view('admin.settings.edit', compact('title', 'model'));
    }

    public function update(Request $request, $id)
    {
        if(isset($request->settings)){
            $this->validate($request, [
                'max_leaves' => 'required|max:255',
                'max_discrepancies' => 'required|max:255',
                'insurance_eligibility' => 'required|max:255',
            ]);
        }else{
            $this->validate($request, [
                'name' => 'required|max:255',
                'phone_number' => 'required',
                'email' => 'required',
                'address' => 'required',
            ]);
        }


        DB::beginTransaction();

        try{
            $model = Setting::where('id', $id)->first();

            if(isset($request->settings)){
                $model->discrepancy_leave_allow_enable_disable = $request->discrepancy_leave_allow_enable_disable;
                $model->max_leaves = $request->max_leaves;
                $model->max_discrepancies = $request->max_discrepancies;
                $model->insurance_eligibility = $request->insurance_eligibility;
            }else{
                if ($request->hasFile('logo')) {
                    $logo = $request->file('logo');
                    $logoName = Str::uuid() . '.' . $logo->getClientOriginalExtension();
                    $logo->move(public_path('admin/assets/img/logo'), $logoName);

                    $model->logo = $logoName;
                }

                if ($request->hasFile('favicon')) {
                    $favicon = $request->file('favicon');
                    $faviconName = Str::uuid() . '.' . $favicon->getClientOriginalExtension();
                    $favicon->move(public_path('admin/assets/img/favicon'), $faviconName);

                    $model->favicon = $faviconName;
                }

                if ($request->hasFile('black_logo')) {
                    $black_logo = $request->file('black_logo');
                    $logoName = Str::uuid() . '.' . $black_logo->getClientOriginalExtension();
                    $black_logo->move(public_path('admin/assets/img/logo'), $logoName);

                    $model->black_logo = $logoName;
                }

                if ($request->hasFile('slip_stamp')) {
                    $slip_stamp = $request->file('slip_stamp');
                    $logoName = Str::uuid() . '.' . $slip_stamp->getClientOriginalExtension();
                    $slip_stamp->move(public_path('admin/assets/img/logo'), $logoName);

                    $model->slip_stamp = $logoName;
                }

                if ($request->hasFile('admin_signature')) {
                    $admin_signature = $request->file('admin_signature');
                    $logoName = Str::uuid() . '.' . $admin_signature->getClientOriginalExtension();
                    $admin_signature->move(public_path('admin/assets/img/logo'), $logoName);

                    $model->admin_signature = $logoName;
                }

                $model->name = $request->name;
                $model->phone_number = $request->phone_number;
                $model->email = $request->email;
                $model->website_url = $request->website_url;
                $model->language = $request->language;
                $model->country = $request->country;
                $model->area = $request->area;
                $model->city = $request->city;
                $model->state = $request->state;
                $model->zip_code = $request->zip_code;
                $model->address = $request->address;
                $model->facebook_link = $request->facebook_link;
                $model->instagram_link = $request->instagram_link;
                $model->linked_in_link = $request->linked_in_link;
                $model->twitter_link = $request->twitter_link;
            }

            $model->save();

            DB::commit();

            \LogActivity::addToLog('Setting details updated');

            return redirect()->route('settings.edit', $id)->with('message', 'Setting details updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
