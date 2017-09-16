<!-- hidden input -->
<div @include('crud::inc.field_wrapper_attributes') >
  <input
  	type="hidden"
    ng-model="item.{{ $field['name'] }}"
    @include('crud::inc.field_attributes')
  	>
</div>