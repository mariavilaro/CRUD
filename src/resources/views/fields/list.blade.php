<!-- array input -->

<?php
    $items = old($field['name']) ? (old($field['name'])) : (isset($field['value']) ? ($field['value']) : (isset($field['default']) ? ($field['default']) : '' ));

    // make sure not matter the attribute casting
    // the $items variable contains a properly defined JSON
    if (is_array($items)) {
        if (count($items)) {
            $items = json_encode($items);
        } else {
            $items = '[]';
        }
    } elseif (is_string($items) && !is_array(json_decode($items))) {
        $items = '[]';
    }

?>
<div ng-app="backPackListApp" ng-controller="listController" @include('crud::inc.field_wrapper_attributes') >

    <label>{!! $field['label'] !!}</label>

    <div class="array-container form-group">

        <table class="table table-bordered table-striped m-b-0" ng-init="field = '#{{ $field['name'] }}'; items = {{ $items }}">

            <thead>
                <tr>

                    @foreach( $field['columns'] as $prop )
                    <th style="font-weight: 600!important;">
                        {{ $prop }}
                    </th>
                    @endforeach
                    <th class="text-center"> </th>
                </tr>
            </thead>

            <tbody ng-model="items" class="table-striped">

                <tr ng-repeat="item in items" class="array-row" >

                    @foreach( $field['columns'] as $prop => $label)
                    <td>
                        <% item.{{ $prop }} %>
                    </td>
                    @endforeach
                    <td>
                        @if (!empty($field['view_button']))
                    	<a href="#" ng-if="item['{{ $field['column_id'] }}']" ng-click="viewItem('{{ url($field['route']) }}', '{{ $field['column_id'] }}', item); $event.preventDefault();" class="btn btn-xs btn-default"><i class="fa fa-eye"></i> {{ trans('backpack::crud.view') }}</a>
                        @endif
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    {{-- @push('crud_fields_styles')
        {{-- YOUR CSS HERE --}}
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        {{-- YOUR JS HERE --}}
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular.min.js"></script>
        <script>

            window.angularApp = window.angularApp || angular.module('backPackListApp', [], function($interpolateProvider){
                $interpolateProvider.startSymbol('<%');
                $interpolateProvider.endSymbol('%>');
            });

            window.angularApp.controller('listController', function($scope){

                $scope.viewItem = function(route, column_id, item){
                    route = route + '/' + item[column_id];
                    window.open(route, '_blank');
                }

            });

            angular.element(document).ready(function(){
                angular.forEach(angular.element('[ng-app]'), function(ctrl){
                    var ctrlDom = angular.element(ctrl);
                    if( !ctrlDom.hasClass('ng-scope') ){
                        angular.bootstrap(ctrl, [ctrlDom.attr('ng-app')]);
                    }
                });
            })

        </script>

    @endpush
@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
