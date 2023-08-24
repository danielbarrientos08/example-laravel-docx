<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NcJoes\OfficeConverter\OfficeConverter;

class DocumentController extends Controller
{
    public function index()
    {
        return view('app');
    }

    public function export(Request $request)
    {
        try {

            $docxFilePath = $this->createDocxFile($request);

            // Define la ruta al directorio de almacenamiento de PDFs

            // Crear una instancia del convertidor
            $converter = new OfficeConverter($docxFilePath);

            $ouputFile = 'file' . time() . '.pdf';
            $converter->convertTo($ouputFile);

            $pdfFilePath = Storage::path($ouputFile);

            // Descargar el archivo PDF
            return response()->download($pdfFilePath, 'constancia.pdf', [
                'Content-Type' => 'application/pdf',
            ])->deleteFileAfterSend();
        } catch (\Exception $th) {
            dd($th->getMessage());
        }
    }

    public function createDocxFile(Request $request)
    {
        try {

            $templatePath = Storage::path('template_document.docx');

            $template = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
            $template->setValue('document', $request->document);
            $template->setValue('fullname', $request->fullname);

            $outputFolderPath = storage_path('app');
            $outputFileName = 'constancia.docx';
            $outputFilePath = $outputFolderPath . '/' . $outputFileName;

            // Crear la carpeta de salida si no existe
            if (!file_exists($outputFolderPath)) {
                mkdir($outputFolderPath, 0755, true);
            }

            // Guardar el archivo generado en la carpeta de salida
            $template->saveAs($outputFilePath);

            return $outputFilePath;
        } catch (\PhpOffice\PhpWord\Exception\Exception $th) {
            return null;
        }
    }
}
