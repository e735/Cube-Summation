<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MatrixTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_inicio()
    {
		 $response = $this->get('/');
		 $response->assertStatus(200);
		 $response->assertSee("Cube Summation");
		 $response->assertDontSee("resultados");

    }
	
	public function test_result() {

		$fileName = 'testfile.txt';
    	$filePath = __DIR__ . '/';

    	$file = new UploadedFile($fileName, $filePath, 'text/plain', null, true);
		
		$response = $this->call('POST', '/', ['testfile' => $file]);
      $response->assertStatus(200);
		$response->assertSee("resultados");
  
	}
}
