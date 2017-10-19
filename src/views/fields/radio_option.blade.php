<label class="radio" for={{ $Field->attributes->id }}>
    <input {!! $Field->attributes !!}>
    {{ $Field->options[$key] }}
</label>
