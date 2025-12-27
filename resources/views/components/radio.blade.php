@props([
    'name' => '',
    'id' => '',
    'label' => '',
    'value' => '',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'containerClass' => 'mb-3',
    'checkClass' => 'form-check',
    'inputClass' => 'form-check-input',
    'labelClass' => 'form-check-label',
    'inline' => false,
    'helpText' => '',
])

@php
    // Auto-generate ID from name and value if not provided
    $inputId = $id ?: ($name . '_' . str_replace(' ', '_', $value));
    
    // Determine if checked (supports old values)
    $isChecked = old($name) ? old($name) == $value : $checked;
    
    // Build check container class
    $checkContainerClass = $checkClass . ($inline ? ' form-check-inline' : '');
@endphp

<div class="{{ $containerClass }}">
    <div class="{{ $checkContainerClass }}">
        <input 
            type="radio" 
            class="{{ $inputClass }}" 
            id="{{ $inputId }}" 
            name="{{ $name }}" 
            value="{{ $value }}"
            {{ $isChecked ? 'checked' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes }}
        >
        <label class="{{ $labelClass }}" for="{{ $inputId }}">
            {{ $label }}
        </label>
    </div>

    @if($helpText)
        <small class="text-muted d-block mt-1">{{ $helpText }}</small>
    @endif

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror

    {{-- jQuery Validation Plugin Error Label (shared for radio group) --}}
    {{-- Note: Only one error label per radio group, not per radio button --}}
</div>
