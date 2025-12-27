@props([
    'name' => '',
    'id' => '',
    'label' => '',
    'required' => false,
    'disabled' => false,
    'accept' => '',
    'multiple' => false,
    'containerClass' => 'mb-3',
    'labelClass' => 'form-label',
    'inputClass' => 'form-control',
    'helpText' => '',
    'preview' => false,
])

@php
    // Auto-generate ID from name if not provided
    $inputId = $id ?: $name;
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

    <input 
        type="file" 
        class="{{ $inputClass }}" 
        id="{{ $inputId }}" 
        name="{{ $name }}" 
        {{ $accept ? 'accept=' . $accept : '' }}
        {{ $multiple ? 'multiple' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}
    >

    @if($helpText)
        <small class="text-muted d-block mt-1">{{ $helpText }}</small>
    @endif

    @if($preview)
        <div id="{{ $inputId }}-preview" class="mt-2"></div>
    @endif

    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror

    {{-- jQuery Validation Plugin Error Label --}}
    <label id="{{ $inputId }}-error" class="text-danger error" for="{{ $inputId }}" style="display: none;"></label>
</div>

@if($preview)
<script>
    document.getElementById('{{ $inputId }}').addEventListener('change', function(e) {
        const preview = document.getElementById('{{ $inputId }}-preview');
        preview.innerHTML = '';
        
        const files = e.target.files;
        if (files.length > 0) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail me-2 mb-2';
                        img.style.maxWidth = '150px';
                        img.style.maxHeight = '150px';
                        preview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'badge bg-secondary me-2 mb-2';
                    fileInfo.textContent = file.name;
                    preview.appendChild(fileInfo);
                }
            });
        }
    });
</script>
@endif
