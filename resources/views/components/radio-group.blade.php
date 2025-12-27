@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'selected' => '',
    'required' => false,
    'disabled' => false,
    'inline' => true,
    'containerClass' => 'mb-3',
    'labelClass' => 'form-label',
    'helpText' => '',
])

@php
    // Get selected value (supports old values)
    $selectedValue = old($name, $selected);
@endphp

<div class="{{ $containerClass }}">
    @if($label)
        <label class="{{ $labelClass }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div>
        @foreach($options as $value => $optionLabel)
            @php
                $radioId = $name . '_' . str_replace(' ', '_', $value);
                $isChecked = $selectedValue == $value;
            @endphp
            
            <div class="form-check {{ $inline ? 'form-check-inline' : '' }}">
                <input 
                    class="form-check-input" 
                    type="radio" 
                    name="{{ $name }}" 
                    id="{{ $radioId }}" 
                    value="{{ $value }}"
                    {{ $isChecked ? 'checked' : '' }}
                    {{ $required ? 'required' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                >
                <label class="form-check-label" for="{{ $radioId }}">
                    {{ $optionLabel }}
                </label>
            </div>
        @endforeach
    </div>

    @if($helpText)
        <small class="text-muted d-block mt-1">{{ $helpText }}</small>
    @endif

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror

    {{-- jQuery Validation Plugin Error Label --}}
    <label id="{{ $name }}-error" class="text-danger error" for="{{ $name }}" style="display: none;"></label>
</div>
