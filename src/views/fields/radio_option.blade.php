{{-- Don't include this, use $Field->makeOptionView() instead --}}
<label class="radio" for={{ $Field->attributes->id }}>
    <input {!! $Field->attributes !!}>
    {{ $Field->options[$key] }}
</label>
