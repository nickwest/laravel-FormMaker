@if(isset($extends) && $extends != '')
    @extends($extends)
@endif

@if(isset($section) && $section != '')
    @section($section)
@endif

@yield('above_form')

<form method="{{ $Form->method }}" action="{{ $Form->url }}" id="{{ $Form->form_id }}"{{ ($Form->multipart ? ' enctype="multipart/form-data"' : '') }}>
    <fieldset>
    @foreach($Form->getDisplayFields() as $field)
        @if($field->type != 'subform')
            @include('form-maker::fields.'.$field->type, array('field' => $field))
        @else
            {!! $field->subform->makeSubformView($field->subform_data)->render() !!}
        @endif
    @endforeach
    </fieldset>

    <fieldset class="submit_button">
        @if(count($Form->getSubmitButtons()) > 0)
            @foreach($Form->getSubmitButtons() as $button)
                <input name="{{ $button['name'] }}" id="submit_button_{{ $button['name'] }}" class="button is-success {{ $button['class'] }}" type="submit" value="{{ $button['label'] }} "/>
            @endforeach
        @else
            <input name="submit_button" id="submit_button_save" class="button is-success save" type="submit" value="{{ (isset($Form->submit_button) ? $Form->submit_button : 'Save') }} "/>
        @endif

        @if($Form->getAllowDelete())
            <input name="submit_button" id="submit_button_delete" class="button is-danger delete" type="submit" value="{{ (isset($Form->delete_button) ? $Form->delete_button : 'Delete') }}" />
        @endif
    </fieldset>
</form>

@yield('below_form')

@if(isset($section) && $section != '')
    @stop
@endif
