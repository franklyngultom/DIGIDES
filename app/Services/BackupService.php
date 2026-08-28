<?php

namespace App\Services;

use App\Models\BackupRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class BackupService
{
    /**
     * Directory inside storage for backup archives.
     */
    protected string $backupDir = 'backups';

    /**
     * Create a new database backup record and file.
     */
    public function createBackup(?int $userId = null): BackupRecord
    {
        $storagePath = storage_path('app/'.$this->backupDir);
        if (! File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $connection = config('database.default');
        $timestamp = date('Y_m_d_His');

        try {
            $dbPath = config("database.connections.{$connection}.database");

            // Direct SQLite file snapshot if not in-memory and no active outer transaction
            if ($connection === 'sqlite' && $dbPath !== ':memory:' && DB::transactionLevel() === 0 && file_exists($dbPath)) {
                $filename = "digides_backup_{$timestamp}.sqlite";
                $targetPath = str_replace('\\', '/', $storagePath.'/'.$filename);

                File::copy($dbPath, $targetPath);
                $fileSize = File::exists($targetPath) ? File::size($targetPath) : 0;

                return BackupRecord::create([
                    'user_id' => $userId,
                    'filename' => $filename,
                    'file_path' => $this->backupDir.'/'.$filename,
                    'size_bytes' => $fileSize,
                    'status' => 'success',
                    'backup_type' => 'sqlite_snapshot',
                ]);
            }

            // Universal SQL Dump (Supports SQLite in-memory, transaction mode, and MySQL)
            $filename = "digides_backup_{$timestamp}.sql";
            $targetPath = str_replace('\\', '/', $storagePath.'/'.$filename);

            $sqlContent = "-- DIGIDES DATABASE BACKUP --\n";
            $sqlContent .= "-- Connection: {$connection} --\n";
            $sqlContent .= '-- Generated at: '.date('Y-m-d H:i:s')." --\n\n";

            if ($connection === 'sqlite') {
                $tables = DB::select("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
                foreach ($tables as $table) {
                    $tableName = $table->name;
                    $sqlContent .= "\n\n-- Table structure for: {$tableName}\n";
                    $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                    $sqlContent .= $table->sql.";\n\n";

                    $rows = DB::table($tableName)->get();
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $escapedValues = array_map(function ($val) {
                            return is_null($val) ? 'NULL' : "'".addslashes((string) $val)."'";
                        }, array_values($rowArray));

                        if (! empty($escapedValues)) {
                            $sqlContent .= "INSERT INTO `{$tableName}` VALUES (".implode(', ', $escapedValues).");\n";
                        }
                    }
                }
            } else {
                $tables = DB::select('SHOW TABLES');
                $dbName = config("database.connections.{$connection}.database");
                $tableKey = "Tables_in_{$dbName}";

                foreach ($tables as $table) {
                    $tableName = $table->$tableKey ?? current((array) $table);
                    $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                    $sqlContent .= "\n\n".($createTable[0]->{'Create Table'} ?? '').";\n\n";

                    $rows = DB::table($tableName)->get();
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $escapedValues = array_map(function ($val) {
                            return is_null($val) ? 'NULL' : "'".addslashes((string) $val)."'";
                        }, array_values($rowArray));

                        if (! empty($escapedValues)) {
                            $sqlContent .= "INSERT INTO `{$tableName}` VALUES (".implode(', ', $escapedValues).");\n";
                        }
                    }
                }
            }

            File::put($targetPath, $sqlContent);
            $fileSize = File::exists($targetPath) ? File::size($targetPath) : 0;

            return BackupRecord::create([
                'user_id' => $userId,
                'filename' => $filename,
                'file_path' => $this->backupDir.'/'.$filename,
                'size_bytes' => $fileSize,
                'status' => 'success',
                'backup_type' => $connection.'_dump',
            ]);
        } catch (Throwable $e) {
            Log::error('Database backup failed', ['error' => $e->getMessage()]);

            return BackupRecord::create([
                'user_id' => $userId,
                'filename' => "failed_backup_{$timestamp}",
                'file_path' => '',
                'size_bytes' => 0,
                'status' => 'failed',
                'backup_type' => $connection,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete a backup file and record.
     */
    public function deleteBackup(BackupRecord $record): bool
    {
        $fullPath = storage_path('app/'.$record->file_path);
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }

        return (bool) $record->delete();
    }
}
