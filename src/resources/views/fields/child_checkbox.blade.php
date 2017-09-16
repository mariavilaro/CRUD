<!-- text input -->
<div>
    <div>
        <input type="checkbox"
            ng-model="item.{{ $field['name'] }}"
            ng-true-value="1" ng-false-value="0"
        >
    </div>
</div>


@if (!$crud->child_resource_included['checkbox'])

    @push('crud_fields_styles')
        <style>
            .table input[type='checkbox'] { padding-right: 5px; }
        </style>
    @endpush

    <?php $crud->child_resource_included['checkbox'] = true; ?>
@endif