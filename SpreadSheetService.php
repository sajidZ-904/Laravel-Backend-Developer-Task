<?php

namespace Tests\Unit\Services;

use App\Jobs\ProcessProductImage;
use App\Models\Product;
use App\Services\SpreadsheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class SpreadsheetServiceTest extends TestCase
{
    use RefreshDatabase; 

    protected $spreadsheetService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->spreadsheetService = new SpreadsheetService();
        Queue::fake(); 
    }

    public function testProcessSpreadsheetValidData()
    {
        
        $filePath = 'path/to/valid/spreadsheet.csv';
        $data = [
            ['product_code' => 'P001', 'quantity' => 10],
            ['product_code' => 'P002', 'quantity' => 5],
        ];

        
        app()->instance('importer', new class($data) {
            public function import($filePath)
            {
                return $this->data;
            }

            private $data;
            public function __construct($data) {
                $this->data = $data;
            }
        });

        
        $this->spreadsheetService->processSpreadsheet($filePath);

        
        $this->assertCount(2, Product::all());
        $this->assertDatabaseHas('products', ['code' => 'P001', 'quantity' => 10]);
        $this->assertDatabaseHas('products', ['code' => 'P002', 'quantity' => 5]);

        
        Queue::assertPushed(ProcessProductImage::class, 2);
    }

    public function testProcessSpreadsheetWithValidationErrors()
    {
        
        $filePath = 'path/to/invalid/spreadsheet.csv';
        $data = [
            ['product_code' => 'P001', 'quantity' => 10],
            ['product_code' => 'P001', 'quantity' => 5], // Duplicate product code
        ];

        
        app()->instance('importer', new class($data) {
            public function import($filePath)
            {
                return $this->data;
            }

            private $data;
            public function __construct($data) {
                $this->data = $data;
            }
        });

        
        $this->spreadsheetService->processSpreadsheet($filePath);

        
        $this->assertCount(1, Product::all());
        $this->assertDatabaseHas('products', ['code' => 'P001', 'quantity' => 10]);

        
        Queue::assertPushed(ProcessProductImage::class, 1);
    }

    public function testProcessSpreadsheetWithInvalidData()
    {
        
        $filePath = 'path/to/invalid/spreadsheet.csv';
        $data = [
            ['product_code' => 'P003'], 
            ['quantity' => 5],          
        ];

        
        app()->instance('importer', new class($data) {
            public function import($filePath)
            {
                return $this->data;
            }

            private $data;
            public function __construct($data) {
                $this->data = $data;
            }
        });

        
        $this->spreadsheetService->processSpreadsheet($filePath);

        
        $this->assertCount(0, Product::all());

        
        Queue::assertNothingPushed();
    }
}
