<?php 

namespace App\Classes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use NcJoes\OfficeConverter\OfficeConverter;

class RtfToPdfClass {

	public function get_data( $filePath )
	{
		$converter = new OfficeConverter($filePath, null, "soffice", config('app.use_remote_libreoffice'));
		
		$fileName = time() . '.pdf';

		$convert = $converter->convertTo( $fileName, storage_path('app/livewire-tmp'));

		if ( !empty($convert) ) {

			$data['fileName'] = $fileName;

			return $data;
		}

		return null;
	}
}