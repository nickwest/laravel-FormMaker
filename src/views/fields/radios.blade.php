@component('form-maker::components.field', ['Field' => $Field])

    @slot('field_markup')
        <div class="options">
            @if($Field->label != '')
                <label class="{{ $Field->label_class }}">{!! $Field->label.($Field->label_postfix != '' ? $Field->label_postfix : '').($Field->is_required ? ' <em>*</em>' : '') !!}</label>
            @endif
            @foreach($Field->options as $key => $option)
                {!! $Field->makeOptionView($key) !!}
            @endforeach
        </div>

        @include('form-maker::pieces.error')
        @include('form-maker::pieces.note')
    @endslot

@endcomponent
