@props([
    'type' => 'text',
    'name' => '',
    'id' => '',
    'label' => '',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'min' => null,
    'max' => null,
    'step' => null,
    'maxlength' => null,
    'helpText' => '',
    'icon' => '',
    'iconPosition' => 'left',
    'containerClass' => 'mb-3',
    'labelClass' => 'form-label',
    'inputClass' => 'form-control',
    'rows' => 3,
    'accept' => '',
    'multiple' => false,
    'pattern' => '',
    'autocomplete' => '',
])

@php
    // Auto-generate ID from name if not provided
    $inputId = $id ?: $name;
    
    // Build input attributes
    $attributes = $attributes->merge([
        'type' => $type !== 'textarea' && $type !== 'select' ? $type : null,
        'name' => $name,
        'id' => $inputId,
        'placeholder' => $placeholder,
        'class' => $inputClass . ($icon ? ' ps-5' : ''),
        'value' => $type !== 'textarea' && $type !== 'select' && $type !== 'file' ? old($name, $value) : null,
        'required' => $required,
        'readonly' => $readonly,
        'disabled' => $disabled,
        'min' => $min,
        'max' => $max,
        'step' => $step,
        'maxlength' => $maxlength,
        'pattern' => $pattern,
        'autocomplete' => $autocomplete,
        'accept' => $type === 'file' ? $accept : null,
        'multiple' => $type === 'file' && $multiple ? true : null,
    ])->filter(function ($value) {
        return $value !== null && $value !== '';
    });
@endphp

<div class="{{ $containerClass }}">
    @if($label)
        <label for="{{ $inputId }}" class="{{ $labelClass }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="position-relative">
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} position-absolute top-50 translate-middle-y ms-3" style="z-index: 10;"></i>
        @endif

        @if($type === 'textarea')
            <textarea {{ $attributes }} rows="{{ $rows }}">{{ old($name, $value) }}</textarea>
        @elseif($type === 'select')
            <select {{ $attributes->except(['type', 'value', 'placeholder']) }}>
                @if($placeholder)
                    <option value="">{{ $placeholder }}</option>
                @endif
                {{ $slot }}
            </select>
        @else
            <input {{ $attributes }}>
        @endif

        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} position-absolute top-50 end-0 translate-middle-y me-3" style="z-index: 10;"></i>
        @endif
    </div>

    @if($helpText)
        <small class="text-muted d-block mt-1">{{ $helpText }}</small>
    @endif

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror

    {{-- jQuery Validation Plugin Error Label --}}
    <label id="{{ $inputId }}-error" class="text-danger error" for="{{ $inputId }}" style="display: none;"></label>
</div>
