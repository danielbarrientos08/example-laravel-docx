<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use NcJoes\OfficeConverter\OfficeConverter;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentController extends Controller
{
    public function index()
    {
        return view('app');
    }

    public function export(Request $request)
    {
        try {
            // Get template path
            $templatePath = public_path('documents/template_constancia.docx');

            $replacementVariables = [
                'document' => $request->document,
                'fullname' => $request->fullname,
                // Add more fields
            ];

            // Create new docx from template
            $docxFilePath = $this->createDocxFile($templatePath, $replacementVariables);

            // Set $bin = soffice for windows and linux
            $converter = new OfficeConverter($docxFilePath, null, 'soffice');

            // set uoput file name (PDF)
            $ouputFile = 'Constancia_' . time() . '.pdf';

            $converter->convertTo($ouputFile);
            // Save file in storage
            $pdfFilePath = Storage::path($ouputFile);

            // Download the pdf file
            $response = response()->download($pdfFilePath, $ouputFile, [
                'Content-Type' => 'application/pdf',
            ]);

            // Delete the pdf file after send the response
            $response->deleteFileAfterSend();

            // Delete the Docx file original
            File::delete($docxFilePath);

            return $response;
        } catch (\Throwable $ex) {

            return back()->with('error', $ex->getMessage());
        }
    }

    private function createDocxFile(string $templatePath, array $replacementVariables)
    {
        try {
            // start template process
            $template = new TemplateProcessor($templatePath);

            // Set values in template
            foreach ($replacementVariables as $variable => $value) {
                $template->setValue($variable, $value);
            }

            // Set
            $outputFolderPath = storage_path('app');
            $outputFileName = time() . 'docx';
            $outputFilePath = $outputFolderPath . '/' . $outputFileName;

            // Create ouput directory if not exists
            if (!file_exists($outputFolderPath)) {
                mkdir($outputFolderPath, 0755, true);
            }

            // Save Docx file in the ouput directory
            $template->saveAs($outputFilePath);

            return $outputFilePath;

        } catch (\PhpOffice\PhpWord\Exception\Exception $th) {
            return $th->getMessage();
        }
    }
}
