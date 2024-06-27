<div>
    
    <form wire:submit.prevent="onEditTool({{ $this->page_id }})">
        <div class="modal-body">
            
			<div class="alert-message">
			  <!-- Session Status -->
			  <x-auth-session-status class="mb-4" :status="session('status')" />
										  
			  <!-- Validation Errors -->
			  <x-auth-validation-errors class="mb-4" :errors="$errors" />
			</div>

            <div class="form-group mb-3">
                <label for="tool" class="form-label">{{ __('Tools') }}</label>
                <div class="input-group">
                    <input class="form-control" type="text" wire:model.defer="tool_name" readonly>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="position" class="form-label">{{ __('Position') }}</label>
                <div class="input-group">
                    <input class="form-control @error('position') is-invalid @enderror" type="number" wire:model.defer="position" id="position" required>
                </div>
                <small class="form-hint">{{ __('Arrange the tools in the order you want.') }}</small>
            </div>

            @if ( $tool_name !== 'custom_tool_link')
                <div class="form-group mb-3">
                    <label for="edit_slug" class="form-label">{{ __('Slug') }}</label>
                    <div class="input-group">
                        <input class="form-control @error('slug') is-invalid @enderror" type="text" wire:model.defer="slug" id="edit_slug" required>
                        <button type="button" class="btn btn-info" wire:click="createSlug">{{ __('Create slug') }}</button>
                    </div>
                    <small class="form-hint">{{ __('Generate SEO-Friendly URL Slug.') }}</small>
                </div>
            @endif

            @if ( $tool_name == 'custom_tool_link')
                <div class="form-group mb-3">
                    <label for="edit_custom_tool_link" class="form-label">{{ __('Link') }}</label>
                    <div class="input-group">
                        <input class="form-control @error('custom_tool_link') is-invalid @enderror" type="text" wire:model.defer="custom_tool_link" id="edit_custom_tool_link" required>
                    </div>
                    <small class="form-hint">{{ __('Enter your custom tool link.') }}</small>
                </div>
            @endif

            <div class="form-group mb-3">
                <label for="edit_tool" class="form-label">{{ __('Categories') }}</label>
                <div class="input-group">
                    <select wire:model.defer="category_id" class="form-control form-select">
                        <option value selected style="display:none;">{{ __('Choose a category...') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ __( $category['id'] ) }}">{{ __( $category['title'] ) }}</option>
                        @endforeach
                    </select>
                </div>
                <small class="form-hint">{{ __('Select the category you want to show.') }}</small>
            </div>
            
            <div class="form-group mb-3">
                <label for="edit_icon_image" class="form-label">{{ __('Icon image') }}</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <a id="edit_icon_image" data-input="icon_edit" class="btn btn-success featured-image">
                            <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                        </a>
                    </span>
                    <input id="icon_edit" class="form-control ps-2" type="text" wire:model.defer="icon_image">
                </div>
                <small class="form-hint">{{ __('This icon will appear before the tool\'s name on the homepage.') }}</small>
            </div>

            @if ( $tool_name !== 'custom_tool_link')
                <div class="form-group mb-3">
                    <label for="edit_featured_image" class="form-label">{{ __('Featured image') }}</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <a id="edit_featured_image" data-input="thumbnail_edit" class="btn btn-success featured-image">
                                <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                            </a>
                        </span>
                        <input id="thumbnail_edit" class="form-control ps-2" type="text" wire:model.defer="featured_image">
                    </div>
                    <small class="form-hint">{{ __('This image will show up on search engines.') }}</small>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">{{ __('Target') }}</label>
                <select class="form-control form-select" wire:model.defer="target">
                    <option value="_self">{{ __('_self') }}</option>
                    <option value="_blank">{{ __('_blank') }}</option>
                </select>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <span>
                    <div wire:loading.inline wire:target="onEditTool">
                        <x-loading />
                    </div>
                    <span>{{ __('Save Changes') }}</span>
                </span>
            </button>
        </div>
    </form>

    <script>
    (function( $ ) {
        "use strict";

        document.addEventListener('livewire:load', function () {

            jQuery('.featured-image').filemanager('image', {prefix: '{{ url('/') }}/filemanager'});

            jQuery('input#thumbnail_edit').change(function() { 
                window.livewire.emit('onSetFeaturedImage', this.value)
            });

            jQuery('input#icon_edit').change(function() { 
                window.livewire.emit('onSetIconImage', this.value)
            });

        });
        
    })( jQuery );
    </script>

</div>
