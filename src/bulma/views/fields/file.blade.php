@formmaker_component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        <label class="{{ $Field->label_class }}" for="{{ $Field->attributes->id }}">
            {{ $Field->label }}{{ $Field->label_suffix ? $Field->label_suffix : '' }}
        </label>
        <div class="file has-name is-fullwidth">
            <label class="file-label">
                <input {!! $Field->attributes !!} />
                <span class="file-cta">
                    <span class="file-icon">
                           <i class="fa fa-upload"></i>
                    </span>
                    <span class="file-label">
                           Choose a file…
                    </span>
                </span>
                <span class="file-name">
                       {{ $Field->value }}
                </span>
                @if($Field->value != '')
                    <input class="button is-danger" type="submit" value="{{ $Field->delete_button_value }}" name="{{ $Field->attributes->name }}" />
                @endif
            </label>
        </div>
    @endslot

@endcomponent
