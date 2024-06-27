<div>

      <form wire:submit.prevent="onCreditCardValidator">

        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

        <div class="row">
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Credit Card Number') }}</label>
                <input type="text" class="form-control" wire:model.defer="code" placeholder="{{ __('Add credit card number here') }}" required />
            </div>

            <div class="form-group mb-3">
                <label class="form-label">{{ __('Credit Card Type') }}</label>
                <select wire:model.defer="type" class="form-control form-select">
                    <option value="amex">{{ __('American Express') }}</option>
                    <option value="diners">{{ __('Diners Club') }}</option>
                    <option value="discover">{{ __('Discover') }}</option>
                    <option value="jcb">{{ __('JCB') }}</option>
                    <option value="mastercard">{{ __('MasterCard') }}</option>
                    <option value="visa">{{ __('Visa') }}</option>
                </select>
            </div>

            @if ( \App\Models\Admin\General::first()->captcha_status )
              <x-public.recaptcha />
            @endif
        
            <div class="form-group mb-3">
                <button class="btn btn-info mx-auto d-block" wire:loading.attr="disabled">
                    <span>
                        <div wire:loading.inline wire:target="onCreditCardValidator">
                            <x-loading />
                        </div>
                        <span wire:target="onCreditCardValidator">{{ __('Check') }}</span>
                    </span>
                </button>
            </div>
        </div>
      </form>

      <div class="form-group">
          <label class="form-label">{{ __('Example credit card numbers') }}</label>
          <div class="table-responsive mt-3">
              <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('Credit Card Type') }}</th>
                        <th>{{ __('Credit Card Number') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">{{ __('American Express') }}</td>
                        <td>{{ __('371449635398431') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">{{ __('Diners Club') }}</td>
                        <td>{{ __('30569309025904') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">{{ __('Discover') }}</td>
                        <td>{{ __('6011111111111117') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">{{ __('JCB') }}</td>
                        <td>{{ __('3530111333300000') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">{{ __('MasterCard') }}</td>
                        <td>{{ __('5555555555554444') }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">{{ __('Visa') }}</td>
                        <td>{{ __('4916592289993918') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
      </div>

        <script>
           (function( $ ) {
              "use strict";

                document.addEventListener('livewire:load', function () {

                      var el = document.getElementById('paste');

                      if(el){

                        el.addEventListener('click', function(paste) {

                            paste = document.getElementById('paste');

                            '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <line x1="4" y1="7" x2="20" y2="7"></line> <line x1="10" y1="11" x2="10" y2="17"></line> <line x1="14" y1="11" x2="14" y2="17"></line> <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path> <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path> </svg>' === paste.innerHTML ? (link.value = "", paste.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path><rect x="9" y="3" width="6" height="4" rx="2"></rect></svg>') : navigator.clipboard.readText().then(function(clipText) {

                                @this.set('link', clipText);

                            }, paste.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"> <path stroke="none" d="M0 0h24v24H0z" fill="none"></path> <line x1="4" y1="7" x2="20" y2="7"></line> <line x1="10" y1="11" x2="10" y2="17"></line> <line x1="14" y1="11" x2="14" y2="17"></line> <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path> <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path> </svg>');

                        });
                      }
                });

          })( jQuery );
        </script>
</div>