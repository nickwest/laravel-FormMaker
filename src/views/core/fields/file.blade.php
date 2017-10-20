@component('form-maker::core.components.field', ['Field' => $Field])

    @slot('field_markup')
        @include('form-maker::core.pieces.label', ['Field' => $Field])

        @if($Field->value == '')
            <input {!! $Field->attributes !!} />
        @else
            <div class="file-link">
                {{ $Field->value }}
                <input type="submit" value="remove" name="{{ $Field->attributes->name }}" />
            </div>
        @endif

        @include('form-maker::core.pieces.error')
        @include('form-maker::core.pieces.note')
    @endslot

@endcomponent
