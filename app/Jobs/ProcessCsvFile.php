<?php

namespace App\Jobs;

use App\Models\FileUpload;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use Exception;
use Illuminate\Support\Facades\Log;


class ProcessCsvFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct(FileUpload $file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        $filePath = storage_path('app/uploads/' . $this->file->file_name);

        $this->file->update(['status' => 'processing']);

        try {
            $csv = Reader::createFromPath($filePath)->setHeaderOffset(0);

            foreach ($csv->getRecords() as $record) {
                $record = array_map('utf8_encode', $record); // Clean non-UTF8

                Product::updateOrCreate(
                    ['unique_key' => $record['UNIQUE_KEY']],
                    [
                        'product_title' => $record['PRODUCT_TITLE'],
                        'product_description' => $record['PRODUCT_DESCRIPTION'],
                        'style' => $record['STYLE#'],
                        'sanmar_mainframe_color' => $record['SANMAR_MAINFRAME_COLOR'],
                        'size' => $record['SIZE'],
                        'color_name' => $record['COLOR_NAME'],
                        'piece_price' => $record['PIECE_PRICE'],
                    ]
                );
            }

            $this->file->update(['status' => 'completed']);
        } catch (Exception $e) {
            Log::error('CSV Processing Failed: ' . $e->getMessage());
            $this->file->update(['status' => 'failed']);
        }
    }
}
