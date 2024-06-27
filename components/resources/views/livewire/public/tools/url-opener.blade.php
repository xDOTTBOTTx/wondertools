<div>

      <form wire:submit.prevent="onUrlOpener">
        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>
    
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Enter each url must be in separate line:') }}</label>
            <textarea class="form-control" wire:model.defer="links" rows="10" placeholder="{{ __('Enter or Paste your URLs here...') }}" required></textarea>
        </div>

        @if ( \App\Models\Admin\General::first()->captcha_status )
          <x-public.recaptcha />
        @endif
        
        <div class="form-group">
            <button class="btn btn-info mx-auto d-block" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onUrlOpener">
                  <x-loading />
                </div>
                <span wire:target="onUrlOpener">{{ __('Open') }}</span>
              </span>
            </button>
        </div>
      </form>

        <script>
           (function( $ ) {
              "use strict";

                document.addEventListener('livewire:load', function () {

                    window.addEventListener('onSetUrlOpener', event => {

                        var link = event.detail.links;
                        $(link).each(function(){
                            window.open(this, '_blank');
                        });

                    });

                });

          })( jQuery );
        </script>
</div>