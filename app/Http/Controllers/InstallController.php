<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Validator;

class InstallController extends Controller
{
    public function __construct() {
        if (file_exists(base_path() . '/install.lock') && Route::getCurrentRoute()->getActionMethod() !== 'showLocked')
            return redirect('install/locked')->send();
    }

    public function getRequirementsChecker()
    {
        return view('install.requirements', [
            'requirements' => $this->checkRequirements('list'),
            'status' => $this->checkRequirements()
        ]);
    }

    public function postRequirementsChecker()
    {
        if ($this->checkRequirements())
            return redirect()->route('install.getDatabase');
        else
            $this->getRequirementsChecker();
    }

    public function getDatabaseSetup()
    {
        return view('install.database');
    }

    public function postDatabaseSetup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required',
            'port' => 'required',
            'database' => 'required',
            'username' => 'required'
        ]);

        if ($validator->fails())
        {
            return redirect()->route('install.getDatabase')->withErrors($validator)->withInput(
                $request->except('password')
            );
        }

        $this->setEnv('DB_HOST', $request->input('host'));
        $this->setEnv('DB_PORT', $request->input('port'));
        $this->setEnv('DB_DATABASE', $request->input('database'));
        $this->setEnv('DB_USERNAME', $request->input('username'));
        $this->setEnv('DB_PASSWORD', $request->input('password'));

        Artisan::call('cache:clear');

        try
        {
            DB::connection()->getPdo();
            Artisan::call('migrate', array('--force' => true));
            
            if ($this->checkMigrations())
                return redirect()->route('install.getAdministrator');
            else
            {
                $errors = [
                    'Could not populate the database with the required tables.',
                    'Error: ' . Artisan::output()
                ];

                return redirect()->route('install.getDatabase')->withErrors($errors)->withInput(
                    $request->except('password')
                );
            }
        }
        catch (\Exception $e)
        {
            $errors = [
                'Could not connect to the database. Please check your configuration.',
                'Error: ' . $e->getMessage()
            ];

            return redirect()->route('install.getDatabase')->withErrors($errors)->withInput(
                $request->except('password')
            );
        }
    }

    public function getCreateAdministrator()
    {
        return view('install.administrator');
    }

    public function postCreateAdministrator(Request $request)
    {
        $validator = Validator::make($request->all(), User::rules());

        if ($validator->fails())
        {
            return redirect()->route('install.getAdministrator')->withErrors($validator)->withInput(
                $request->except(['password', 'password_confirmation'])
            );
        }
        
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'role' => 10
        ]);

        return redirect()->route('install.getSuccess');
    }

    public function getSuccess() {
        return view('install.success');
    }

    public function getLocked()
    {
        return view('install.locked');
    }

    private function checkRequirements($function = null)
    {
        $requirements['php_version'] = [
            'message' => 'PHP version is higher than 7.1.3.',
            'status' => version_compare(PHP_VERSION, "7.1.3", ">=")
        ];

        $requirements['php_openssl'] = [
            'message' => 'PHP OpenSSL Extension is installed and loaded.',
            'status' => extension_loaded("openssl")
        ];

        $requirements['php_pdo'] = [
            'message' => 'PHP PDO Extension is installed and loaded.',
            'status' => defined('PDO::ATTR_DRIVER_NAME')
        ];

        $requirements['php_mbstring'] = [
            'message' => 'PHP Mbstring Extension is installed and loaded.',
            'status' => extension_loaded("mbstring")
        ];

        $requirements['php_tokenizer'] = [
            'message' => 'PHP Tokenizer Extension is installed and loaded.',
            'status' => extension_loaded("tokenizer")
        ];

        $requirements['php_xml'] = [
            'message' => 'PHP XML Extension is installed and loaded.',
            'status' => extension_loaded("xml")
        ];

        $requirements['php_ctype'] = [
            'message' => 'PHP Ctype Extension is installed and loaded.',
            'status' => extension_loaded("ctype")
        ];

        $requirements['php_json'] = [
            'message' => 'PHP JSON Extension is installed and loaded.',
            'status' => extension_loaded("json")
        ];

        $requirements['writable_storage'] = [
            'message' => 'The storage directory \'/storage\' is writable.',
            'status' => is_writable(storage_path())
        ];

        $requirements['writable_cache'] = [
            'message' => 'The cache directory \'/bootstrap/cache\' is writable.',
            'status' => is_writable(base_path('bootstrap/cache'))
        ];

        if ($function == 'list')
            return $requirements;
        else
        {
            // Get status (true/false) by filtering the array of requirements.
            return empty(array_filter($requirements, function($requirement) {
                // Remove all enabled requirements from the array.
                return !$requirement['status'];
            }));
        }
    }

    private function checkMigrations() {
        $dbQuery = DB::select('select migration from migrations');
        $migrations = scandir(database_path('migrations'));

        $dbMigrations = [];

        foreach ($dbQuery as $row)
        {
            array_push($dbMigrations, $row->migration . '.php');
        }

        if (empty(array_diff($migrations, $dbMigrations)))
            return true;
        else
            return false;
    }

    private function setEnv($key, $value) {
        $env = base_path('.env');

        return file_put_contents($env, preg_replace('/' . $key . '=(.*)' . PHP_EOL . '/', $key . '=' . $value . PHP_EOL, file_get_contents($env)));
    }
}
