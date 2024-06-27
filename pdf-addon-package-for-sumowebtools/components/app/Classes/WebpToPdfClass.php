<?php 

namespace App\Classes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use NcJoes\OfficeConverter\OfficeConverter;

class WebpToPdfClass {


    /**
     * -------------------------------------------------------------------------------
     *  get_data
     * -------------------------------------------------------------------------------
    **/
	public function get_data($filePath)
	{
	    try {
	        $convertedFilePath = $this->convertWebpToPdf($filePath);

	        if (!empty($convertedFilePath)) {

	            $data['fileName'] = $convertedFilePath;

	            return $data;
	        }

	        return null;

	    } catch (\Exception $e) {
	        Log::error('An error occurred while converting the file to PDF: ' . $e->getMessage());
	        return null;
	    }
	}

    /**
     * -------------------------------------------------------------------------------
     *  convertWebpToPdf
     * -------------------------------------------------------------------------------
    **/
	private function convertWebpToPdf($filePath)
	{
	    $jpegImage = storage_path('app/livewire-tmp/') . time() . '.jpg'; // Path to save the converted JPEG image

	    // Convert WebP to JPG using PHP GD
	    $img = imagecreatefromwebp($filePath);
	    imagejpeg($img, $jpegImage, 100);
	    imagedestroy($img);

	    // Convert JPG to PDF using office-converter
	    $converter = new OfficeConverter($jpegImage, null, "soffice", config('app.use_remote_libreoffice'));
	    $fileName = time() . '.pdf';
	    $convert = $converter->convertTo($fileName, storage_path('app/livewire-tmp'));

	    // Delete the intermediate JPEG image
	    unlink($jpegImage);

	    if (!empty($convert)) {
	        return $fileName;
	    }

	    return null;
	}
}