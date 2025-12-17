<?php 
use App\Models\ActivityLog;
    function activityLog($user_id = 0,$description = '',$action = '',$module = '')
        {
            ActivityLog::create([
                'user_id' => $user_id,
                'action' => $action,
                'module' => $module,
                'description' => $description,
            ]);
        }