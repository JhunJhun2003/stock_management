<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = Setting::getSettings();
        $backups = $this->getBackupFiles();
        
        return view('pos.setting', compact('settings', 'backups'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $settings = Setting::getSettings();

        $validated = $request->validate([
            'shop_name' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'currency_code' => 'nullable|string|max:10',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'language' => 'nullable|string|max:10',
        ]);

        $settings->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Settings updated successfully!');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('settings.index')
                ->with('error', 'Current password is incorrect!');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('settings.index')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Create database backup.
     */
    public function createBackup()
    {
        try {
            // Get database connection
            $database = DB::connection()->getDatabaseName();
            $tables = DB::select('SHOW TABLES');
            
            // Get table names
            $tableNames = array_map('current', $tables);
            
            // Create backup directory
            $backupDir = storage_path('app/backups');
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0777, true);
            }
            
            // Create backup file
            $filename = 'backup_' . date('Y_m_d_His') . '.sql';
            $filepath = $backupDir . '/' . $filename;
            
            // Generate backup content
            $output = "-- POS System Backup\n";
            $output .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
            
            foreach ($tableNames as $table) {
                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `$table`");
                if (!empty($createTable)) {
                    $output .= "DROP TABLE IF EXISTS `$table`;\n";
                    $output .= $createTable[0]->{'Create Table'} . ";\n\n";
                }
                
                // Get table data
                $rows = DB::table($table)->get();
                if ($rows->count() > 0) {
                    $output .= "INSERT INTO `$table` VALUES\n";
                    $rowValues = [];
                    foreach ($rows as $row) {
                        $values = array_map(function($value) {
                            return $value === null ? 'NULL' : "'" . addslashes($value) . "'";
                        }, (array)$row);
                        $rowValues[] = "(" . implode(',', $values) . ")";
                    }
                    $output .= implode(",\n", $rowValues) . ";\n\n";
                }
            }
            
            $output .= "SET FOREIGN_KEY_CHECKS=1;\n";
            
            // Save file
            file_put_contents($filepath, $output);
            
            return redirect()->route('settings.index')
                ->with('success', 'Backup created successfully: ' . $filename);
                
        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    /**
     * Restore database from backup.
     */
    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|string',
        ]);

        try {
            $filename = $request->backup_file;
            $filepath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($filepath)) {
                return redirect()->route('settings.index')
                    ->with('error', 'Backup file not found!');
            }
            
            // Read backup file
            $sql = file_get_contents($filepath);
            
            // Split SQL statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Execute each statement
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    DB::statement($statement);
                }
            }
            
            // Enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            return redirect()->route('settings.index')
                ->with('success', 'Database restored successfully from: ' . $filename);
                
        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Restore failed: ' . $e->getMessage());
        }
    }

    /**
     * Get list of backup files.
     */
    private function getBackupFiles()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];
        
        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            $files = array_filter($files, function($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
            });
            
            foreach ($files as $file) {
                $filepath = $backupDir . '/' . $file;
                $backups[] = [
                    'name' => $file,
                    'date' => date('M d, Y - h:i A', filemtime($filepath)),
                    'size' => $this->formatFileSize(filesize($filepath)),
                ];
            }
            
            // Sort by date (newest first)
            usort($backups, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }
        
        return $backups;
    }

    /**
     * Format file size.
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}