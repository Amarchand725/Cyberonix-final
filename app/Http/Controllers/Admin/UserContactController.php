<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LogActivity;
use App\Models\UserContact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class UserContactController extends Controller
{
    public function store(Request $request)
    {
        if($request->type=='emergency_contact'){
            $this->validate($request, [
                'name' => ['required', 'max:255'],
                'relationship' => ['required','max:50'],
                'phone_number' => 'required|min:12',
                'email' => 'sometimes|nullable|email',
                'address_details' => ['required', 'max:255'],
                'city' => ['required'],
                'country' => ['required'],
            ],
            [
                'phone_number.required' => 'The phone number field is required.',
                'phone_number.numeric' => 'The phone number must be a numeric value.',
                'phone_number.min' => 'The phone number must be at least 11 digits.',
            ]);
        }else{
            $this->validate($request, [
                'phone_number' => 'nullable|min:12',
                'details' => ['required', 'max:255'],
                'area' => ['required','max:50'],
                'city' => ['required', 'max:50'],
                'state' => ['required', 'max:50'],
                'country' => ['required'],
            ],
            [
                'phone_number.required' => 'The phone number field is required.',
                'phone_number.numeric' => 'The phone number must be a numeric value.',
                'phone_number.min' => 'The phone number must be at least 11 digits.',
            ]);
        }

        DB::beginTransaction();

        try{
            $inputs = $request->except(['type', '_token']);
            UserContact::create([
                'user_id' => Auth::user()->id,
                'key' => $request->type,
                'value' => json_encode($inputs),
            ]);

            DB::commit();

            \LogActivity::addToLog('User contact added successfully');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $model = UserContact::where('id', $id)->first();
        $details = json_decode($model->value);

        if($model->key=='emergency_contact'){
            return (string) view('admin.user-contacts.emergency_edit_content', compact('model', 'details'));
        }
        $address_details = $details;
        return (string) view('admin.user-contacts.address_edit_content', compact('model', 'address_details'));
    }

    public function update(Request $request, $id)
    {
        if($request->type=='emergency_contact'){
            $this->validate($request, [
                'name' => ['required', 'max:255'],
                'relationship' => ['required','max:50'],
                'phone_number' => 'required|min:12',
                'email' => 'sometimes|nullable|email',
                'address_details' => ['required', 'max:255'],
                'city' => ['required'],
                'country' => ['required'],
            ],
            [
                'phone_number.required' => 'The phone number field is required.',
                'phone_number.numeric' => 'The phone number must be a numeric value.',
                'phone_number.min' => 'The phone number must be at least 11 digits.',
            ]);
        }else{
            $this->validate($request, [
                'phone_number' => 'nullable|min:12',
                'details' => ['required', 'max:255'],
                'area' => ['required','max:50'],
                'city' => ['required', 'max:50'],
                'state' => ['required', 'max:50'],
                'country' => ['required'],
            ],
            [
                'phone_number.required' => 'The phone number field is required.',
                'phone_number.numeric' => 'The phone number must be a numeric value.',
                'phone_number.min' => 'The phone number must be at least 11 digits.',
            ]);
        }

        DB::beginTransaction();

        try{
            $inputs = $request->except(['type', '_token']);
            UserContact::where('id', $id)->update([
                'value' => json_encode($inputs),
            ]);

            DB::commit();

            \LogActivity::addToLog('User contact updated successfully');

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function destroy(UserContact $userContact)
    {
        if(isset($userContact) && !empty($userContact)){
            $historyArray['model_id'] = $userContact->id;
            $historyArray['model_name'] = "\App\Models\UserContact";
            $historyArray['type'] = "1";
            $historyArray['remarks'] = "User Contact has been deleted";
            $model = $userContact->delete();
            if($model) {
                LogActivity::addToLog('User Contact Deleted');
                LogActivity::deleteHistory($historyArray);
                return response()->json([
                    'status' => true,
                ]);
            } else{
                return false;
            }
        } else{
            return false;
        }
    }
}
