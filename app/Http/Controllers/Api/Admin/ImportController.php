<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportController extends Controller
{
    public function template(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="product_import_template.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['product_name', 'category', 'price', 'description', 'image_url']);
            fputcsv($handle, ['Grilled Chicken', 'Main Dishes', '8500', 'Tender grilled chicken with herbs', 'https://example.com/chicken.jpg']);
            fputcsv($handle, ['Iced Coffee', 'Beverages', '2000', 'Cold brew with ice', '']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls'],
        ]);

        $import = new ProductsImport;

        Excel::import($import, $request->file('file'));

        return response()->json([
            'summary' => [
                'imported' => $import->imported,
                'skipped' => $import->skipped,
                'duplicates' => $import->duplicates,
                'total_errors' => count($import->errors),
            ],
            'errors' => $import->errors,
        ]);
    }
}
