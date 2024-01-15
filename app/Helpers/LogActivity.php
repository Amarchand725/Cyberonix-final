<?php


namespace App\Helpers;

use App\Models\DeleteHistory;
use Illuminate\Support\Facades\Request;
use App\Models\LogActivity as LogActivityModel;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

class LogActivity
{
	public static function addToLog($subject)
	{
		$log = [];
		$log['subject'] = $subject;
		$log['url'] = Request::fullUrl();
		$log['method'] = Request::method();
		$log['ip'] = Request::ip();
		$log['agent'] = Request::header('user-agent');
		$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
		LogActivityModel::create($log);
	}


	public static function logActivityLists($per_record)
	{
		return LogActivityModel::latest()->paginate($per_record);
	}


	public static function deleteHistory($array)
	{
		SystemLog::create([
			"creator_id" => Auth::user()->id,
			"model_id" => $array['model_id'] ?? null,
			"model_name" => $array['model_name'] ?? null,
			"type" => $array['type'] ?? null,
			"ip" => Request::ip(),
			"event_id" => $array['event_id'] ?? null,
			"remarks" => $array['remarks'] ?? null,
            "old_data" => isset($array['old_data']) && !empty($array['old_data']) ? json_encode($array['old_data']) : null,
            "new_data" => isset($array['new_data']) && !empty($array['new_data']) ? json_encode($array['new_data']) : null,
		]);
	}
	public static function saveLog($array)
	{
		SystemLog::create([
			"creator_id" => Auth::user()->id,
			"model_id" => $array['model_id'] ?? null,
			"model_name" => $array['model_name'] ?? null,
			"type" => $array['type'] ?? null,
			"ip" => Request::ip(),
			"event_id" => $array['event_id'] ?? null,
			"remarks" => $array['remarks'] ?? null,
            "old_data" => isset($array['old_data']) && !empty($array['old_data']) ? json_encode($array['old_data']) : null,
            "new_data" => isset($array['new_data']) && !empty($array['new_data']) ? json_encode($array['new_data']) : null,
		]);
	}
}
