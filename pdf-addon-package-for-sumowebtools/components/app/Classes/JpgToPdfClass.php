<?php 

namespace App\Classes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use NcJoes\OfficeConverter\OfficeConverter;
use Image;

class JpgToPdfClass {

	public function get_data( $filePath )
	{

        $img = Image::make( $filePath )->encode('png');

        $fileName = time() . '.png';

        $img->save( storage_path('app/livewire-tmp/') . $fileName );

        $imgPath = storage_path('app/livewire-tmp/') . $fileName;

		$converter = new OfficeConverter($imgPath, null, "soffice", config('app.use_remote_libreoffice'));
		
		$fileName = time() . '.pdf';

		$convert = $converter->convertTo( $fileName, storage_path('app/livewire-tmp'));

		if ( !empty($convert) ) {

			$data['fileName'] = $fileName;

			return $data;
		}

		return null;
	}
}