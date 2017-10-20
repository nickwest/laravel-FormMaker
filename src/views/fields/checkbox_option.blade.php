{{-- Don't include this, use $Field->makeOptionView() instead --}}
<div class="{{ $Field->option_wrapper_class }}">
    <label class="{{ $Field->option_label_class }}" for={{ $Field->attributes->id }}>
        <input {!! $Field->attributes !!}>
        {{ $Field->options[$key] }}
    </label>
</div>
