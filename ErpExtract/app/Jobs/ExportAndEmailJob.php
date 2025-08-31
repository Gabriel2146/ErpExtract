<?php

namespace App\Jobs;

use App\Exports\DynamicExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class ExportAndEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $headings;
    protected $userEmail;
    protected $tablaCodigo;
    protected $username;

    public function __construct(array $data, array $headings, string $userEmail, string $tablaCodigo, string $username)
    {
        $this->data = $data;
        $this->headings = $headings;
        $this->userEmail = $userEmail;
        $this->tablaCodigo = $tablaCodigo;
        $this->username = $username;
    }

    public function handle()
    {
        $filename = "TABLA_" . date('dmy') . "_{$this->username}.xlsx";
        $zipFilename = storage_path("app/exports/TABLA_" . date('dmy') . "_{$this->username}.zip");

        // Crear carpeta temporal si no existe
        if (!file_exists(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0777, true);
        }

        $excelPath = storage_path("app/exports/{$filename}");

        // Generar Excel
        Excel::store(new DynamicExport($this->data, $this->headings), "exports/{$filename}");

        // Comprimir
        $zip = new ZipArchive();
        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $zip->addFile($excelPath, $filename);
            $zip->close();
        }

        // Enviar email
        Mail::raw("El proceso de exportación de la tabla {$this->tablaCodigo} ha finalizado. Adjuntamos el archivo.", function ($message) use ($zipFilename) {
            $message->to($this->userEmail)
                ->subject("Exportación de tabla {$this->tablaCodigo}")
                ->attach($zipFilename);
        });

        // Eliminar Excel temporal si quieres
        @unlink($excelPath);
    }
}
