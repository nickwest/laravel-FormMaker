<form method="{{ $Form->method }}" action="{{ $Form->url }}" id="{{ $Form->form_id }}"{{ ($Form->multipart ? ' enctype="multipart/form-data"' : '') }}>
    <div class="fields">
        @foreach($Form->getDisplayFields() as $field)
            @include('form-maker::fields.'.$field->type, array('field' => $field))
        @endforeach
    </div>

    <div class="submit-buttons">
        <div class="field">
            <p class="control">
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
            </p>
        </div>
    </div>
</form>
