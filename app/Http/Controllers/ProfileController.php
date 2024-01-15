<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;

use Illuminate\Support\Str;
use App\Models\Profile;
use Illuminate\View\View;
use App\Models\UserContact;
use Illuminate\Http\Request;
use App\Models\SalaryHistory;
use App\Models\JobHistory;
use Illuminate\Validation\Rules;
use App\Models\ProfileCoverImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AssetUser;
use App\Models\Document;
use App\Models\DocumentAttachments;
use Carbon\Carbon;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $title = 'Profile';
        $model = $request->user();
        // $job_histories = SalaryHistory::orderby('id','desc')->where('user_id', $model->id)->get();
        $job_histories = JobHistory::orderby('id', 'desc')->where('user_id', $model->id)->get();
        $joining_date = DB::table('job_histories')->select('joining_date')->where('user_id', $model->id)->first();
        $cover_images = ProfileCoverImage::orderby('id', 'desc')->where('status', 1)->take(4)->get();
        $user_permanent_address = UserContact::where('user_id', $model->id)->where('key', 'permanent_address')->first();
        $user_current_address = UserContact::where('user_id', $model->id)->where('key', 'current_address')->first();
        $user_emergency_contacts = UserContact::where('user_id', $model->id)->where('key', 'emergency_contact')->get();

        return view(
            'admin.profile.my-profile',
            compact('title', 'model', 'job_histories', 'joining_date', 'cover_images', 'user_permanent_address', 'user_current_address', 'user_emergency_contacts')
        );
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->validate([
            'first_name' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $user = $request->user();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->save();

            if ($user) {
                $profile = Profile::where('user_id', $user->id)->first();

                $profile_image = '';
                if ($request->hasFile('profile')) {
                    $image = $request->file('profile');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('admin/assets/img/avatars'), $imageName);

                    $profile_image = $imageName;
                }

                $cnic_front = '';
                if ($request->hasFile('cnic_front')) {
                    $image = $request->file('cnic_front');
                    $imageName = time() . Str::random(5) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('admin/assets/img/avatars'), $imageName);

                    $cnic_front = $imageName;
                }

                $cnic_back = '';
                if ($request->hasFile('cnic_back')) {
                    $image = $request->file('cnic_back');
                    $imageName = time() . Str::random(5) . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('admin/assets/img/avatars'), $imageName);

                    $cnic_back = $imageName;
                }

                $martial_status = 0;
                if (!empty($request->marital_status)) {
                    $martial_status = $request->marital_status;
                }

                if (!empty($profile)) {
                    $profile->cnic = $request->cnic;
                    $profile->gender = $request->gender;
                    $profile->phone_number = $request->phone_number;
                    $profile->cover_image_id = $request->cover_image_id;
                    $profile->date_of_birth = $request->date_of_birth;
                    $profile->marital_status = $martial_status;
                    $profile->about_me = $request->about_me;

                    if (!empty($profile_image)) {
                        $profile->profile = $profile_image;
                    }
                    if (!empty($cnic_front)) {
                        $profile->cnic_front = $cnic_front;
                    }
                    if (!empty($cnic_back)) {
                        $profile->cnic_back = $cnic_back;
                    }

                    $profile->save();
                } else {
                    $profile = new Profile();
                    $profile->user_id = $request->user()->id;
                    $profile->cnic = $request->cnic;
                    $profile->gender = $request->gender;
                    $profile->phone_number = $request->phone_number;
                    $profile->cover_image_id = $request->cover_image_id;
                    $profile->date_of_birth = $request->date_of_birth;
                    $profile->marital_status = $martial_status;
                    $profile->about_me = $request->about_me;
                    $profile->profile = $profile_image;
                    $profile->cnic_front = $cnic_front;
                    $profile->cnic_back = $cnic_back;
                    $profile->save();
                }

                DB::commit();
            }

            \LogActivity::addToLog('Profile Updated');

            return redirect()->back()->with('message', 'Profile Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => false, 'message' => 'The provided old password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true, 'message' => 'You have changed password successfully!.']);
    }

    public function getUserDocuments(Request $request)
    {
        $title = 'All Documents';
        $user = Auth::user();
        $model = [];
        Document::where("user_id", Auth::user()->id)->orderby("id", "desc")
            ->chunk(100, function ($documents) use (&$model) {
                foreach ($documents as $document) {
                    $model[] = $document;
                }
            });
        if ($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn("attachment_count", function ($row) {
                    $docs = "";
                    $docs .= '<img src="' . asset('public/admin/assets/img/fileicon.png') . '" style="width:30px" alt="">';
                    $docs .= '<span class="ms-2 d-inline-block fw-bold" style="font-size:13px;">';
                    $docs .= !empty($row->hasAttachments)  ? $row->hasAttachments->count() : 0;
                    $docs .= '</span>';

                    return $docs;
                })
                ->addColumn('date', function ($model) {
                    return Carbon::parse($model->date)->format('d, M Y');
                })
                ->addColumn('status', function ($model) {
                    $label = '';
                    switch ($model->status) {
                        case 1:
                            $label = '<span class="badge bg-label-success" text-capitalized="">Active</span>';
                            break;
                        case 0:
                            $label = '<span class="badge bg-label-danger" text-capitalized="">De-active</span>';
                            break;
                    }

                    return $label;
                })
                ->addColumn('action', function ($model) {
                    return view('admin.profile.documents.action', ['model' => $model])->render();
                })
                ->rawColumns(['attachment_count', 'date', 'status', 'action'])
                ->make(true);
        }
    }
    public function storeDocumentAsUser(Request $request)
    {

        DB::beginTransaction();
        try {
            $model = Document::create([
                'user_id' => Auth::user()->id,
                'date' => date('y-m-d'),
            ]);
            if ($model && count($request->titles) > 0 && count($request->attachments) > 0) {
                foreach ($request->titles as $key => $title) {
                    $attachment = '';
                    if ($request->attachments[$key]) {
                        $attachment = $request->attachments[$key];
                        $attachmentName = "DOCUMENT-"  . time() . "-" . rand() . '.' . $attachment->getClientOriginalExtension();
                        $attachment->move(public_path('admin/assets/document_attachments'), $attachmentName);
                        $attachment = $attachmentName;
                    }
                    DocumentAttachments::create([
                        'document_id' => $model->id,
                        'title' => $title,
                        'attachment' => $attachment,
                    ]);
                }
            }
            DB::commit();
            LogActivity::addToLog('New document Added');
            return redirect()->back()->with("message", "Documents have been uploaded");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    public function viewDocuments($id)
    {
        $model = Document::find($id);
        return view('admin.profile.documents.show_content', compact('model'));
    }
    public function editDocuments($id)
    {
        $data['model'] = Document::where('id', $id)->first();
        return  view('admin.profile.documents.edit_content', $data);
    }
    public function updateDocuments(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $model = Document::find($id);
            if (!empty($model->hasAttachments)) {
                foreach ($model->hasAttachments as $index => $value) {
                    $fileName = $value->attachment ?? null;
                    $updateArray = [];
                    $updateArray = $request->titles[$index] ?? null;
                    if (isset($request->attachments) && !empty($request->attachments)) {
                        if (isset($request->attachments[$index]) && !empty($request->attachments[$index])) {
                            $attachment = '';
                            if ($request->attachments[$index]) {
                                $attachment = $request->attachments[$index];
                                $attachmentName =   "DOCUMENT-"  . time() . "-" . rand() . '.' . $attachment->getClientOriginalExtension();
                                $attachment->move(public_path('admin/assets/document_attachments'), $attachmentName);
                                $fileName = $attachmentName;
                            }
                        }
                    }
                    $update = $value->update([
                        "title" => $request->titles[$index] ?? null,
                        "attachment" => $fileName ?? null,
                    ]);
                }
            }
            DB::commit();
            LogActivity::addToLog('Document Updated');
            return redirect()->back()->with("message", "Documents have been updated");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    public function deleteDocuments($id)
    {
        try {
            $attachment = DocumentAttachments::find($id);
            // @unlink(public_path('admin/assets/document_attachments/') . . $attachment->attachment);
            if (!empty($attachment)) {
                // @unlink(public_path('admin/assets/document_attachments/') . . $attachment->attachment);
                $historyArray['model_id'] = $attachment->id;
                $historyArray['model_name'] = "\App\Models\DocumentAttachments";
                $historyArray['type'] = "1";
                $historyArray['remarks'] = "Document Attachment has been deleted";
                $model = $attachment->delete();
                if($model) {
                    LogActivity::addToLog('Document Attachment Deleted');
                    LogActivity::deleteHistory($historyArray);
                    return response()->json(['success' => true, "message" =>  "Document Deleted!"]);
                } else{
                    return false;
                }
            } else{
                return false;
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, "message" => $e->getMessage()]);
        }
    }
    public function deleteDocumentWithAttachments($id)
    {
        try {
            $document = Document::find($id);
            if (!empty($document)) {
                if (!empty($document->hasAttachments)) {
                    foreach ($document->hasAttachments as $attach) {
                        $historyArray['model_id'] = $attach->id;
                        $historyArray['model_name'] = "\App\Models\DocumentAttachments";
                        $historyArray['type'] = "1";
                        $historyArray['remarks'] = "Document has been deleted with Attachments";
                        LogActivity::deleteHistory($historyArray);
                        $attach->delete();
                    }
                }

                $delete = $document->delete();
                if ($delete >  0) {
                    return response()->json(['success' => true, "message" =>  "Document Deleted!"]);
                }
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, "message" => $e->getMessage()]);
        }
    }
    public function myAssets(Request $request)
    {
        $model = [];
        AssetUser::where("employee_id", Auth::user()->id)->where("status", 1)->latest()
            ->chunk(100, function ($assets) use (&$model) {
                foreach ($assets as $asset) {
                    $model[] = $asset;
                }
            });
        if ($request->ajax()) {
            return DataTables::of($model)
                ->addIndexColumn()
                ->addColumn('uid', function ($model) {
                    return $model->asset->uid ?? '-';
                })
                ->addColumn('category', function ($model) {
                    return $model->asset->asset->category->name ?? '-';
                })
                ->addColumn('name', function ($model) {
                    return $model->asset->asset->name ?? "-";
                })
                ->addColumn('date', function ($model) {
                    return !empty($model->effective_date) ? formatDate($model->effective_date)  : '-';
                })
                ->rawColumns(['name', 'status', 'date'])
                ->make(true);
        }
    }
}
