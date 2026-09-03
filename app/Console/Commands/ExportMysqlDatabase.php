<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExportMysqlDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export-mysql {--file=digides.sql : Output filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export complete SQLite/application database to MySQL-compatible .sql file for phpMyAdmin import';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Memulai pembuatan database dump MySQL (phpMyAdmin ready)...');

        $outputFile = base_path($this->option('file'));
        $dbName = 'digides';

        $sql = "-- ========================================================\n";
        $sql .= "-- DIGIDES v2 - Sistem Informasi Desa Sukamaju\n";
        $sql .= "-- Database Export ready for phpMyAdmin / MySQL / MariaDB\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- ========================================================\n\n";

        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
        $sql .= "SET AUTOCOMMIT = 0;\n";
        $sql .= "START TRANSACTION;\n";
        $sql .= "SET time_zone = \"+07:00\";\n\n";

        $sql .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
        $sql .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
        $sql .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
        $sql .= "/*!40101 SET NAMES utf8mb4 */;\n\n";

        $sql .= "--\n";
        $sql .= "-- Database: `{$dbName}`\n";
        $sql .= "--\n";
        $sql .= "CREATE DATABASE IF NOT EXISTS `{$dbName}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
        $sql .= "USE `{$dbName}`;\n\n";

        $tables = Schema::getTableListing();

        // Sort tables so dependencies are handled gracefully
        $priorityOrder = [
            'migrations',
            'permissions',
            'roles',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
            'users',
            'password_reset_tokens',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'desa_profiles',
            'penduduks',
            'penduduk_documents',
            'penduduk_mutasis',
            'surat_templates',
            'surat_arsips',
            'buku_ekspedisis',
            'buku_agendas',
            'buku_peraturan_desa',
            'buku_keputusan_kades',
            'buku_inventaris_aset',
            'buku_tanah_desa',
            'buku_anggaran_desa',
            'buku_lembaran_desa',
            'institutions',
            'institution_members',
            'institution_decisions',
            'institution_activities',
            'institution_agendas',
            'aparatur',
            'keuangan_apbdes',
            'keuangan_kas_transaksi',
            'keuangan_rabs',
            'keuangan_rab_items',
            'pembangunan_proyek',
            'pembangunan_kader',
            'pembangunan_inventaris_hasil',
            'schedules',
            'activity_log',
            'backup_records',
        ];

        $rawTables = Schema::getTableListing();

        // Clean and filter table names
        $cleanTables = [];
        foreach ($rawTables as $t) {
            $parts = explode('.', $t);
            $tableName = end($parts);
            $dbPrefix = count($parts) > 1 ? $parts[0] : null;

            if ($dbPrefix && !in_array($dbPrefix, ['digides', 'main'])) {
                continue;
            }

            if (in_array($tableName, $priorityOrder) && !in_array($tableName, $cleanTables)) {
                $cleanTables[] = $tableName;
            }
        }

        // Sort based on priority order
        usort($cleanTables, function ($a, $b) use ($priorityOrder) {
            $posA = array_search($a, $priorityOrder);
            $posB = array_search($b, $priorityOrder);
            if ($posA === false && $posB === false) return strcmp($a, $b);
            if ($posA === false) return 1;
            if ($posB === false) return -1;
            return $posA - $posB;
        });

        // Special string primary key tables (should not be auto increment)
        $stringPkTables = [
            'sessions' => 'id',
            'cache' => 'key',
            'cache_locks' => 'key',
            'password_reset_tokens' => 'email',
        ];

        foreach ($cleanTables as $table) {
            if ($table === 'sqlite_sequence') {
                continue;
            }

            $this->line("Exporting table: <info>{$table}</info>");

            $sql .= "-- --------------------------------------------------------\n\n";
            $sql .= "--\n";
            $sql .= "-- Struktur tabel untuk `{$table}`\n";
            $sql .= "--\n\n";
            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";

            $columns = Schema::getColumns($table);
            $indexes = Schema::getIndexes($table);

            $columnDefs = [];
            $primaryKeys = [];

            foreach ($columns as $col) {
                $name = $col['name'];
                $typeName = strtolower($col['type_name']);
                $nullable = $col['nullable'] ? 'NULL' : 'NOT NULL';
                
                // Format default value cleanly
                $rawDefault = $col['default'];
                $default = '';
                if ($rawDefault !== null) {
                    $cleanDefault = trim($rawDefault, "'\"");
                    if (is_numeric($cleanDefault)) {
                        $default = "DEFAULT {$cleanDefault}";
                    } elseif ($cleanDefault === 'NULL' || $cleanDefault === 'null') {
                        $default = "DEFAULT NULL";
                    } elseif ($cleanDefault === 'CURRENT_TIMESTAMP' || str_contains(strtoupper($cleanDefault), 'CURRENT_TIMESTAMP')) {
                        $default = "DEFAULT CURRENT_TIMESTAMP";
                    } else {
                        $default = "DEFAULT '" . addslashes($cleanDefault) . "'";
                    }
                } elseif ($col['nullable']) {
                    $default = 'DEFAULT NULL';
                }

                $autoIncrement = $col['auto_increment'] ?? false;
                $isStringPk = isset($stringPkTables[$table]) && $stringPkTables[$table] === $name;

                // Map sqlite types to rich MySQL types
                $mySqlType = match ($typeName) {
                    'integer', 'int' => ($name === 'id' && !$isStringPk) ? 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT' : 'INT',
                    'bigint' => ($name === 'id' && !$isStringPk) ? 'BIGINT UNSIGNED NOT NULL AUTO_INCREMENT' : 'BIGINT',
                    'tinyint', 'boolean' => 'TINYINT(1)',
                    'float', 'double', 'real' => 'DOUBLE',
                    'decimal', 'numeric' => 'DECIMAL(15,2)',
                    'text' => 'TEXT',
                    'mediumtext' => 'MEDIUMTEXT',
                    'longtext' => 'LONGTEXT',
                    'date' => 'DATE',
                    'datetime', 'timestamp' => 'DATETIME',
                    'time' => 'TIME',
                    'blob' => 'LONGBLOB',
                    default => 'VARCHAR(255)',
                };

                // Specific override for specific columns
                if ($table === 'sessions' && $name === 'id') {
                    $mySqlType = 'VARCHAR(255)';
                }
                if ($table === 'sessions' && $name === 'payload') {
                    $mySqlType = 'LONGTEXT';
                }
                if ($table === 'cache' && $name === 'value') {
                    $mySqlType = 'MEDIUMTEXT';
                }
                if ($table === 'activity_log' && ($name === 'properties' || $name === 'batch_uuid')) {
                    $mySqlType = $name === 'properties' ? 'JSON' : 'VARCHAR(255)';
                }

                if (str_contains($mySqlType, 'AUTO_INCREMENT')) {
                    $columnDefs[] = "  `{$name}` {$mySqlType}";
                    $primaryKeys[] = "`{$name}`";
                } else {
                    $def = "  `{$name}` {$mySqlType} {$nullable}";
                    if ($default) {
                        $def .= " {$default}";
                    }
                    $columnDefs[] = $def;
                }
            }

            // Primary Keys & Unique Indexes
            foreach ($indexes as $index) {
                if ($index['primary'] ?? false) {
                    $cols = implode(', ', array_map(fn($c) => "`{$c}`", $index['columns']));
                    if (!in_array("  PRIMARY KEY ({$cols})", $columnDefs)) {
                        $columnDefs[] = "  PRIMARY KEY ({$cols})";
                    }
                } elseif ($index['unique'] ?? false) {
                    $cols = implode(', ', array_map(fn($c) => "`{$c}`", $index['columns']));
                    $idxName = $index['name'];
                    $columnDefs[] = "  UNIQUE KEY `{$idxName}` ({$cols})";
                }
            }

            // Ensure primary key exists if there was an id column
            if (!empty($primaryKeys)) {
                $hasPk = false;
                foreach ($columnDefs as $d) {
                    if (str_starts_with(trim($d), 'PRIMARY KEY')) {
                        $hasPk = true;
                        break;
                    }
                }
                if (!$hasPk) {
                    $columnDefs[] = "  PRIMARY KEY (" . implode(', ', $primaryKeys) . ")";
                }
            }

            $sql .= "CREATE TABLE `{$table}` (\n";
            $sql .= implode(",\n", $columnDefs);
            $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;\n\n";

            // Export Data
            $rows = DB::table($table)->get();

            if ($rows->isNotEmpty()) {
                $sql .= "--\n";
                $sql .= "-- Dumping data untuk tabel `{$table}`\n";
                $sql .= "--\n\n";

                $chunks = $rows->chunk(50);
                foreach ($chunks as $chunk) {
                    $firstRow = (array) $chunk->first();
                    $colNames = implode(', ', array_map(fn($k) => "`{$k}`", array_keys($firstRow)));

                    $valuesList = [];
                    foreach ($chunk as $row) {
                        $valArray = (array) $row;
                        $escapedVals = array_map(function ($val) {
                            if (is_null($val)) {
                                return 'NULL';
                            }
                            if (is_numeric($val) && !is_string($val)) {
                                return $val;
                            }
                            // Check if string is a numeric string with leading zeros e.g. NIK, NIP, Phone, Kode
                            if (is_string($val) && is_numeric($val) && (str_starts_with($val, '0') && strlen($val) > 1)) {
                                return "'" . addslashes($val) . "'";
                            }
                            return "'" . addslashes($val) . "'";
                        }, $valArray);

                        $valuesList[] = "(" . implode(', ', $escapedVals) . ")";
                    }

                    $sql .= "INSERT INTO `{$table}` ({$colNames}) VALUES\n";
                    $sql .= implode(",\n", $valuesList) . ";\n";
                }
                $sql .= "\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
        $sql .= "COMMIT;\n\n";
        $sql .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
        $sql .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
        $sql .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";

        file_put_contents($outputFile, $sql);

        // Also copy to database/digides.sql for convenience
        $databaseSqlPath = database_path('digides.sql');
        file_put_contents($databaseSqlPath, $sql);

        $sizeKb = round(strlen($sql) / 1024, 2);
        $this->info("Berhasil! File SQL MySQL telah dibuat:");
        $this->info("1. {$outputFile} ({$sizeKb} KB)");
        $this->info("2. {$databaseSqlPath} ({$sizeKb} KB)");

        return Command::SUCCESS;
    }
}
