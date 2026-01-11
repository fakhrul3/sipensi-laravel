<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InkubatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if table already has data
        if (DB::table('inkubator')->count() > 0) {
            $this->command->info('Tabel inkubator sudah berisi data. Dilewati.');
            return;
        }

        // Path to SQL file
        $sqlFile = storage_path('app/inkubator_fix.sql');
        
        // Alternative path: check in Downloads folder
        if (!File::exists($sqlFile)) {
            $sqlFile = 'C:\Users\fakhr\Downloads\inkubator fix.sql';
        }

        if (!File::exists($sqlFile)) {
            $this->command->error('File SQL inkubator tidak ditemukan di: ' . $sqlFile);
            $this->command->info('Silakan copy file "inkubator fix.sql" ke: storage/app/inkubator_fix.sql');
            return;
        }

        $this->command->info('Membaca file SQL: ' . $sqlFile);
        
        // Read SQL file line by line for better memory management
        $handle = fopen($sqlFile, 'r');
        if (!$handle) {
            $this->command->error('Tidak bisa membuka file SQL: ' . $sqlFile);
            return;
        }

        $insertStatements = [];
        $currentStatement = '';
        $inInsert = false;

        while (($line = fgets($handle)) !== false) {
            $trimmedLine = trim($line);
            
            // Skip comments and empty lines
            if (empty($trimmedLine) || strpos($trimmedLine, '--') === 0 || strpos($trimmedLine, '/*') === 0) {
                continue;
            }

            // Check if this line starts an INSERT statement
            if (preg_match('/INSERT\s+INTO\s+[`"]?inkubator[`"]?/i', $trimmedLine)) {
                if (!empty($currentStatement)) {
                    $insertStatements[] = $currentStatement;
                }
                $currentStatement = $trimmedLine;
                $inInsert = true;
            } elseif ($inInsert) {
                // Continue building the current INSERT statement
                $currentStatement .= ' ' . $trimmedLine;
                
                // Check if this line ends the statement (contains semicolon)
                if (strpos($trimmedLine, ';') !== false) {
                    $insertStatements[] = $currentStatement;
                    $currentStatement = '';
                    $inInsert = false;
                }
            }
        }

        // Don't forget the last statement if file doesn't end with semicolon
        if (!empty($currentStatement)) {
            $insertStatements[] = $currentStatement;
        }

        fclose($handle);

        if (empty($insertStatements)) {
            $this->command->error('Tidak ada INSERT statement yang ditemukan dalam file SQL');
            return;
        }

        $this->command->info('Menemukan ' . count($insertStatements) . ' INSERT statement(s)');
        
        // Execute each INSERT statement
        $totalInserted = 0;
        foreach ($insertStatements as $index => $insertStatement) {
            try {
                // Clean up the statement - remove backticks around table name
                $cleanStatement = preg_replace('/[`"]inkubator[`"]/', 'inkubator', trim($insertStatement));
                
                // Remove trailing semicolon if exists and add it back
                $cleanStatement = rtrim($cleanStatement, ';') . ';';
                
                DB::unprepared($cleanStatement);
                $totalInserted++;
                
                if (($index + 1) % 5 == 0 || ($index + 1) == count($insertStatements)) {
                    $this->command->info('Memproses... (' . ($index + 1) . '/' . count($insertStatements) . ' statements)');
                }
            } catch (\Exception $e) {
                $this->command->warn('Error pada INSERT statement #' . ($index + 1) . ': ' . $e->getMessage());
                // Continue with next statement
            }
        }

        // Set auto increment
        try {
            DB::statement('ALTER TABLE inkubator AUTO_INCREMENT = 1405');
        } catch (\Exception $e) {
            // Ignore if auto increment setting fails
        }

        $this->command->info('Data inkubator berhasil diimpor (' . $totalInserted . ' INSERT statements dieksekusi)');
        $this->command->info('Total records di tabel inkubator: ' . DB::table('inkubator')->count());
    }
}
