<div id="field-{{ $Field->attributes->name.($Field->multi_key != '' ? '_'.$Field->multi_key : '') }}" class="field {{ $Field->container_class }}{{ ($Field->attributes->required ? ' is-required' : '') }}">

    @if($Field->label != '')
        <label class="label" for="{{ $Field->attributes->id }}">
            {{ $Field->label }}{{ $Field->label_suffix ? $Field->label_suffix : '' }}
        </label>
    @endif

    <div class="control">
        <input {!! $Field->attributes !!} />
    </div>
    @include('form-maker::pieces.error')
    @include('form-maker::pieces.note')

</div>
