@php
	// if not otherwise specified, the hidden input should take up no space in the form
    if (!isset($field['wrapperAttributes']) || !isset($field['wrapperAttributes']['class']))
    {
        $field['wrapperAttributes']['class'] = "hidden";
    }
@endphp

<!-- hidden input -->
<div @include('crud::inc.field_wrapper_attributes') >
  <input
  	type="hidden"
    name="{{ $field['name'] }}"
    @if (!old($field['name']) && isset($field['url_param']))
        value="{{ app('request')->input($field['url_param']) }}"
    @else
    value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
    @endif
    @include('crud::inc.field_attributes')
  	>
</div>