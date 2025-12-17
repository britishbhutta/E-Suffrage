<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupController extends Controller
{
    public function index()
    {
        return view('setup.index');
    }

    public function testConnection(Request $request)
    {
        $validated = $request->validate([
            'DB_DATABASE' => 'required',
            'DB_HOST' => 'required',
            'DB_PORT' => 'required|numeric',
            'DB_USERNAME' => 'required',
            'DB_PASSWORD' => 'nullable',
            'APP_NAME' => 'required'
        ], [], [
            'DB_DATABASE' => 'Database Name',
            'DB_HOST' => 'Database Host',
            'DB_PORT' => 'Database Port',
            'DB_USERNAME' => 'Database Username',
            'DB_PASSWORD' => 'Database Password',
            'APP_NAME' => 'Application Name'
        ]);

        config([
            'database.connections.temp' => [
                'driver' => 'mysql',
                'host' => $validated['DB_HOST'],
                'port' => $validated['DB_PORT'],
                'database' => $validated['DB_DATABASE'],
                'username' => $validated['DB_USERNAME'],
                'password' => $validated['DB_PASSWORD'],
            ],
        ]);

        try {
            DB::connection('temp')->getPdo();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function save(Request $request)
    {
        $data = $request->only([
            'APP_NAME',
            'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'
        ]);

        $path = base_path('.env');
        $env = file_get_contents($path);

        foreach ($data as $key => $value) {
            $escapedValue = '"' . addslashes($value) . '"'; // wrap in quotes for safety
            if (preg_match("/^{$key}=/m", $env)) {
                $env = preg_replace("/^{$key}=.*/m", "{$key}={$escapedValue}", $env);
            } else {
                $env .= "\n{$key}={$escapedValue}";
            }
        }

        // Mark setup complete
        if (preg_match("/^APP_SETUP_COMPLETED=/m", $env)) {
            $env = preg_replace("/^APP_SETUP_COMPLETED=.*/m", "APP_SETUP_COMPLETED=true", $env);
        } else {
            $env .= "\nAPP_SETUP_COMPLETED=true";
        }

        file_put_contents($path, $env);
            return response()->json([
            'success' => true,
            'redirect' => url('/setup/complete')
        ]);
    }
public function createDatabase(Request $request)
{
    $validated = $request->validate([
        'DB_HOST' => 'required',
        'DB_PORT' => 'required|numeric',
        'DB_USERNAME' => 'required',
        'DB_PASSWORD' => 'nullable',
        'DB_DATABASE' => 'required',
    ]);

    try {
        $dbName = $validated['DB_DATABASE'];

        // Step 1: Create a connection without a specific DB
        config([
            'database.connections.temp' => [
                'driver' => 'mysql',
                'host' => $validated['DB_HOST'],
                'port' => $validated['DB_PORT'],
                'database' => null,
                'username' => $validated['DB_USERNAME'],
                'password' => $validated['DB_PASSWORD'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
        ]);

        // Step 2: Create the new database
        DB::connection('temp')->statement("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Step 3: Update config to use the new database
        config(['database.connections.temp.database' => $dbName]);

        // 🧠 Step 4: Reconnect to refresh PDO with the new DB selected
        DB::purge('temp');
        DB::reconnect('temp');

        // Step 5: Run migrations using that connection
        \Artisan::call('migrate', [
            '--force' => true,
            '--database' => 'temp',
        ]);

        $output = \Artisan::output();

        return response()->json([
            'success' => true,
            'message' => 'Database created and migrated successfully.',
            'output'  => $output,
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ]);
    }
}



}

