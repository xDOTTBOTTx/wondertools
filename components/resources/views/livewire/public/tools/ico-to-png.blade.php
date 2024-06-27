<div>
      <form wire:submit.prevent="onIcoToPng">

        <div class="image-container mb-3">

            <div>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                                            
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
            </div>
            
            <div class="image-wrapper {{ ($convertType == 'remoteURL') ? 'show-remote-box' : '' }}">

                <div class="local-image-box dropzone d-flex flex-column p-3">
                    <div class="d-flex mt-auto mx-auto w-75">
                      <div class="row w-100">
                          <div class="col">
                            <div class="form-group">
                              <input type="file" class="form-control" wire:model.defer="local_image" accept=".ico" {{ $convertType == 'localImage' ? 'required' : '' }}>
                            </div>
                          </div>
                          <p class="mt-3 text-center">{{ __('Maximum upload file size') }}: {{ \App\Models\Admin\General::first()->file_size }} {{ __(' MB') }}</p>
                      </div>
                    </div>

                    <div class="d-flex mt-auto flex-end">
                        <small class="ms-auto badge bg-cyan-lt cursor-pointer" wire:click="onConvertType('remoteURL')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /></svg>
                            {{ __('Use Remote URL') }}
                        </small>
                    </div>
                </div>

                <div class="remote-box d-flex flex-column">
                      <div class="d-flex mt-auto mx-auto w-75">
                        <div class="row w-100">
                            <div class="col">
                                <div class="input-group input-group-flat">
                                    <input type="text" id="remote_url" class="form-control" wire:model.defer="remote_url" placeholder="https://..." {{ $convertType == 'remoteURL' ? 'required' : '' }}>
                                    <span class="input-group-text">
                                        <div id="paste" class="cursor-pointer" title="{{ __('Paste') }}" data-bs-original-title="{{ __('Paste') }}" data-bs-toggle="tooltip" wire:ignore>
                                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><rect x="9" y="3" width="6" height="4" rx="2" /></svg>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                      </div>

                      <div class="d-flex mt-auto flex-end">
                          <small class="ms-auto badge bg-cyan-lt cursor-pointer" wire:click="onConvertType('localImage')">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="3" y1="19" x2="21" y2="19" /><rect x="5" y="6" width="14" height="10" rx="1" /></svg>
                              {{ __('Upload from device') }}
                          </small>
                      </div>
                </div>

            </div>
        </div>

        @if ( \App\Models\Admin\General::first()->captcha_status )
          <x-public.recaptcha />
        @endif
        
        <div class="form-group">
            <button class="btn btn-info mx-auto d-block" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onIcoToPng">
                  <x-loading />
                </div>
                <span>{{ __('Convert') }}</span>
              </span>
            </button>
        </div>

          @if ( !empty($data) )
            <div class="table-responsive mt-3" wire:loading.remove wire:target="onIcoToPng">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>{{ __('Thumbnail') }}</th>
                    <th>{{ __('Size') }}</th>
                    <th>{{ __('Action') }}</th>
                  </tr>
                </thead>
                <tbody>                
                    @foreach ($data as $element)
                      <tr>
                        <td class="align-middle"><span class="avatar" style="background-image: url({{ $element['thumbnail'] }})"></span></td>
                        <td class="align-middle">{{ $element['width'] . 'x' . $element['height'] }}px</td>
                        <td class="align-middle"><a href="{{ $element['thumbnail'] }}" class="btn btn-success" title="{{ __('Download') }}" download="{{ \App\Models\Admin\General::orderBy('id', 'DESC')->first()->prefix . time() }}">{{ __('Download') }}</a></td>
                      </tr>
                    @endforeach               
                </tbody>
              </table>
            </div>
          @endif

      </form>
	  
		<script>
  		(function( $ ) {
  		  "use strict";

  			document.addEventListener('livewire:load', function () {

          var el      = document.getElementById('paste');
          var input   = document.getElementById('remote_url');
          var tooltip = new bootstrap.Tooltip(el);

          var pasteIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path><rect x="9" y="3" width="6" height="4" rx="2"></rect></svg>';
          var clearIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <line x1="4" y1="7" x2="20" y2="7"></line> <line x1="10" y1="11" x2="10" y2="17"></line> <line x1="14" y1="11" x2="14" y2="17"></line> <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path> <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path> </svg>';

          function setPasteIcon() {
            el.innerHTML = pasteIcon;
            tooltip.dispose();
            el.setAttribute('title', '{{ __('Paste') }}');
            el.classList.remove('text-danger');
            tooltip = new bootstrap.Tooltip(el);
          }

          function setClearIcon() {
            el.innerHTML = clearIcon;
            tooltip.dispose();
            el.setAttribute('title', '{{ __('Clear') }}');
            el.classList.add('text-danger');
            tooltip = new bootstrap.Tooltip(el);
          }

          function checkInputValue() {
            if (input.value) {
              setClearIcon();
            } else {
              setPasteIcon();
            }
          }

          checkInputValue(); // Initial check in case there's a value already

          // Handle click on the icon
          el.addEventListener('click', function() {
            if (el.innerHTML === clearIcon) {
              // Clear action
              @this.set('remote_url', ''); // Update Livewire state
              setPasteIcon();
            } else {
              // Paste action
              navigator.clipboard.readText().then(function(clipText) {
                @this.set('remote_url', clipText);
                setClearIcon();
              }).catch(function() {
                // Handle error if needed
              });
            }
          });

          // Handle changes to the input field
          input.addEventListener('input', checkInputValue);

          //onSetRemoteURL
  				jQuery('input#remote_url').change(function() { 
  				  window.livewire.emit('onSetRemoteURL', this.value)
  				});

  			});

  		})( jQuery );
		</script>
</div>