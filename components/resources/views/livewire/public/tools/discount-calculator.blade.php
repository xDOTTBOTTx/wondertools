<div>

      <form wire:submit.prevent="onDiscountCalculator">

        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
                                        
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
        </div>

        <div class="form-group mb-3 ">
            <label class="form-label">{{ __('Original price') }}</label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" step="any" wire:model.defer="price" class="form-control" required>
          </div>
        </div>

        <div class="form-group mb-3 ">
            <label class="form-label">{{ __('Discount Percentage') }}</label>
            <div class="input-group">
                <input type="number" step="any" wire:model.defer="discount" class="form-control" required>
                <span class="input-group-text">%</span>
          </div>
        </div>

        @if ( \App\Models\Admin\General::first()->captcha_status )
          <x-public.recaptcha />
        @endif
        
        <div class="form-group text-center">
            <button class="btn btn-info" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onDiscountCalculator">
                  <x-loading />
                </div>
                <span>{{ __('Calculate') }}</span>
              </span>
            </button>

            <button class="btn btn-lime" wire:click.prevent="onSample" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onSample">
                  <x-loading />
                </div>
                <span>{{ __('Sample') }}</span>
              </span>
            </button>

            <button class="btn btn-warning" wire:click.prevent="onReset" wire:loading.attr="disabled">
              <span>
                <div wire:loading.inline wire:target="onReset">
                  <x-loading />
                </div>
                <span>{{ __('Reset') }}</span>
              </span>
            </button>
        </div>

        @if ( !empty($data) )
            <div class="row mt-3">
                <div class="col-12">
                    <table class="table text-center bg-muted text-white">
                        <tbody>
                            <tr>
                                <td class="pb-2 p-4 border border-white">
                                    <p class="fw-bold h3">${{ $data['saving'] }}</p>
                                    <p>{{ __('Saving') }}</p>
                                </td>
                                <td class="pb-2 p-4 border border-white">
                                    <p class="fw-bold h3">${{ $data['discounted_price'] }}</p>
                                    <p>{{ __('Discounted Price') }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
      </form>
</div>