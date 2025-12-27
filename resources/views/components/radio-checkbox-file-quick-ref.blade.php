{{-- ============================================ --}}
{{-- RADIO, CHECKBOX & FILE COMPONENTS - QUICK REFERENCE --}}
{{-- ============================================ --}}

{{-- RADIO GROUP (Recommended) --}}
<x-radio-group 
    name="stock_type" 
    label="Stock Type" 
    :options="['limited' => 'Limited', 'unlimited' => 'Unlimited']"
    selected="limited"
    required
/>

{{-- INDIVIDUAL RADIO BUTTONS --}}
<x-radio name="discount_type" value="flat" label="Flat" checked inline />
<x-radio name="discount_type" value="percentage" label="Percentage" inline />

{{-- CHECKBOX --}}
<x-checkbox name="is_featured" value="1" label="Featured Product" />
<x-checkbox name="is_returnable" value="1" label="Returnable" checked />

{{-- FILE INPUT --}}
<x-file-input name="thumbnail" label="Thumbnail" accept="image/*" />
<x-file-input name="gallery[]" label="Gallery" accept="image/*" multiple />
<x-file-input name="photo" label="Photo" accept="image/*" preview />

{{-- COMMON PROPS --}}
{{--
    RADIO:
    - name, value, label, checked, required, disabled, inline, help-text
    
    RADIO-GROUP:
    - name, label, options (array), selected, required, disabled, inline, help-text
    
    CHECKBOX:
    - name, value, label, checked, required, disabled, inline, help-text
    
    FILE-INPUT:
    - name, label, accept, multiple, required, disabled, preview, help-text
--}}
