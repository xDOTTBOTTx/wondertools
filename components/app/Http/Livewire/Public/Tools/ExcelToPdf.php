<?php

namespace App\Http\Livewire\Public\Tools;

use Livewire\Component;
use App\Models\Admin\History;
use Illuminate\Support\Facades\Http;
use App\Classes\ExcelToPdfClass;
use Livewire\WithFileUploads;
use DateTime;
use Illuminate\Support\Facades\Storage;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use App\Models\Admin\General;
use NcJoes\OfficeConverter\OfficeConverter;
use App\Rules\VerifyRecaptcha;

class ExcelToPdf extends Component
{

    use WithFileUploads;

    protected $listeners = ['onSetRemoteURL'];
    public $convertType = 'localFile';
    public $remote_url;
    public $local_file;
    public $data = [];
    public $recaptcha;
    public $generalSettings;

    public function mount()
    {
        $this->generalSettings = General::first();
    }
	
    public function render()
    {
        return view('livewire.public.tools.excel-to-pdf');
    }

    /**
		* -------------------------------------------------------------------------------
     *  onSetRemoteURL
     * -------------------------------------------------------------------------------
     **/
    public function onSetRemoteURL($value)
    {
      $this->remote_url = $value;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onConvertType
     * -------------------------------------------------------------------------------
     **/
    public function onConvertType( $type ){
        $this->convertType = $type;
    }

    /**
     * -------------------------------------------------------------------------------
     *  onExcelToPdf
     * -------------------------------------------------------------------------------
     **/
    public function onExcelToPdf()
    {
        $this->validateInputs();

        $this->data = null;

        try {
            if ($this->convertType == 'remoteURL') {
                $filePath = $this->handleRemoteFile();
            } else {
                $filePath = $this->handleLocalFile();
            }

            if ($this->isValidFile($filePath)) {
                $this->processFile($filePath);
                $this->dispatchBrowserEvent('resetReCaptcha');
            } else {
                $this->addError('error', __('The file must be of type: xls, xlsx.'));
            }

        } catch (\Exception $e) {
            $this->addError('error', __($e));
        }

        $this->saveHistory();
    }

    /**
     * -------------------------------------------------------------------------------
     *  validateInputs
     * -------------------------------------------------------------------------------
     **/
    private function validateInputs()
    {
        $validationRules = $this->convertType == 'remoteURL'
            ? ['remote_url' => 'required|url']
            : ['local_file' => 'required|mimes:xls,xlsx|file|max:' . 1024 * General::first()->file_size];

        // Add captcha validation if captcha status is enabled
        if ( $this->generalSettings->captcha_status && ($this->generalSettings->captcha_for_registered || !auth()->check()) ) {
            $validationRules['recaptcha'] = ['required', new VerifyRecaptcha];
        }

        $this->validate($validationRules);
    }

    /**
     * -------------------------------------------------------------------------------
     *  handleRemoteFile
     * -------------------------------------------------------------------------------
     **/
    private function handleRemoteFile()
    {
        $fileName = pathinfo($this->remote_url, PATHINFO_BASENAME);
        $fileNameTemp = time() . '.' . pathinfo($this->remote_url, PATHINFO_EXTENSION);
        Storage::disk('local')->put('livewire-tmp/' . $fileNameTemp, Http::get($this->remote_url));
        return storage_path('app/livewire-tmp/') . $fileNameTemp;
    }

    /**
     * -------------------------------------------------------------------------------
     *  handleLocalFile
     * -------------------------------------------------------------------------------
     **/
    private function handleLocalFile()
    {
        $previewPath = $this->local_file->store('livewire-tmp');
        return storage_path('app/') . $previewPath;
    }

    /**
     * -------------------------------------------------------------------------------
     *  isValidFile
     * -------------------------------------------------------------------------------
     **/
    private function isValidFile($filePath)
    {
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        return in_array($fileExtension, ['xls', 'xlsx']);
    }

    /**
     * -------------------------------------------------------------------------------
     *  processFile
     * -------------------------------------------------------------------------------
     **/
    private function processFile($filePath)
    {
        $output = new ExcelToPdfClass();
        $this->data = $output->get_data($filePath);

        if (!empty($this->data)) {
            $url  = asset('components/storage/app/livewire-tmp/' . $this->data['fileName']);
            $data['url'] = $url;
            $data['filename'] = General::orderBy('id', 'DESC')->first()->prefix . time();
            $data['type'] = 'pdf';
            $dlLink = url('/') . '/dl.php?token=' . $this->encode(json_encode($data));
            $this->dispatchBrowserEvent('showModal', ['id' => 'modalPreviewDownloadFile', 'download' => $dlLink, 'preview' => $url ]);
        }
    }

    /**
     * -------------------------------------------------------------------------------
     *  saveHistory
     * -------------------------------------------------------------------------------
     **/
    private function saveHistory()
    {
        if (!empty($this->data)) {
            $history = new History;
            $history->tool_name = 'Excel to PDF';
            $history->client_ip = request()->ip();

            require app_path('Classes/geoip2.phar');
            $reader = new Reader(app_path('Classes/GeoLite2-City.mmdb'));

            try {
                $record = $reader->city(request()->ip());
                $history->flag = strtolower($record->country->isoCode);
                $history->country = strip_tags($record->country->name);
            } catch (AddressNotFoundException $e) {
            }

            $history->created_at = new DateTime();
            $history->save();
        }
    }

    /**
     * -------------------------------------------------------------------------------
     *  encode
     * -------------------------------------------------------------------------------
    **/
    private function encode($pData)
    {
        $encryption_key = 'wondertoolsdotcom';

        $encryption_iv = '9999999999999999';

        $ciphering = "AES-256-CTR"; 
          
        $encryption = openssl_encrypt($pData, $ciphering, $encryption_key, 0, $encryption_iv);

        return $encryption;
    }

}
