@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')
            <div class="col-md-10">
                <div class="main-body sponsor">
                    <section>
                        <div class="container-fluid">                            
                            <div class="row row-eq-height">                                
                                <div class="col-md-12 text-center">
                                    <div class="main-body" style="padding-top:100px">
                                        <h1 style="color:inherit">Manage your sponsors areas</h1>
                                        <h2>Please choose country you interested in</h2>
                                        <div class="clear20"></div>
                                        <div class="col-md-3 hidden-xs hidden-sm"></div>
                                        <div class="col-md-6 relative">
                                            <div class="form-group form-group-border sponsor-country-name">
                                                <label>Country</label>
                                                <div class="font-black"><span>{{$country->name}}</span><i class="fa fa-angle-down pull-right"></i></div>
                                            </div>
                                            <div class="option-box sponsor-country-list hidden">
                                                <!-- <div class="option-head">
                                                    <div class="col-md-10">
                                                        <div class="checkbox">
                                                            <label>
                                                            <input type="checkbox" id="all-check">
                                                            <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span> 
                                                            Edit list of countries
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <div class="delete country">
                                                            <a><img src="/assets/img/delete-icon.png" class="img-responsive" alt="Delete Icon"></a>
                                                        </div>
                                                    </div>
                                                </div> -->
                                                <div class="option-list">
                                                    <div class="col-md-12">
                                                        <ul id="total_countries">
                                                        @foreach($countries as $_country)
                                                        <li data-id="{{$_country->id}}" class="pointer">
                                                            <a href="/admin/sponsors?country_id={{$_country->id}}">{{$_country->name}}</a>
                                                            {!! Form::open([
                                                                'method'=>'DELETE',
                                                                'url' => ['/admin/sponsors/countries', $_country->id],
                                                                'style' => 'display:inline',
                                                                'id' => 'delete-role'
                                                            ]) !!}
                                                                {!! Form::image('/assets/img/delete-icon.png', 'Delete', ['class' => 'pull-right img-responsive', 'style' => 'width: 22px;']) !!}
                                                            {!! Form::close() !!}
                                                        </li>
                                                        @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="option-footer">
                                                    <a class="add-country pointer">+</a>
                                                    <!-- <button type="button" class="add-country pointer" data-toggle="modal" data-target="#myModal">+</button> -->
                                                </div>
                                            </div>
                                        </div>  
                                        <!-- <div class="col-md-4 text-left">
                                            <div class="row">
                                                <button type="button" class="community-btn" style="margin:0px">Submit</button>
                                            </div>
                                        </div> -->
                                        <div class="clear"></div>
                                        <div class="col-md-12" id="state-body" >
                                            <div class="select-state">
                                                <h3>Now you should select State</h3>
                                                <div class="row">
                                                    @foreach($states as $_state)
                                                    <div class="col-sm-2">
                                                        {!! Form::open([
                                                            'method'=>'DELETE',
                                                            'url' => ['/admin/sponsors/states', $_state->id],
                                                            'style' => 'display:inline',
                                                            'id' => 'delete-role'
                                                        ]) !!}
                                                            <button class="close" type="submit">X</button>
                                                        {!! Form::close() !!}
                                                        <a class="state {{ $state->id == $_state->id? 'selected': ''}}" href="/admin/sponsors?country_id={{$_state->country_id}}&state_id={{$_state->id}}">{{$_state->name}}</a>
                                                    </div>
                                                    @endforeach
                                                    <div class="col-sm-2 text-left">
                                                        <a class="add-state pointer" data-toggle="modal" data-target="#addStateModal">+</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="col-md-1 hidden-xs hidden-sm"></div>
                                        @if (isset($state))
                                        <div class="table-box col-md-10 box-display">
                                            <div class="table-head">
                                                <h3>Sponsors Table</h3>
                                                <div class="top-right">
                                                    <!-- <div class="col-md-4">
                                                        <div class="row">
                                                            <select class="form-control">
                                                                <option value="">Sort By</option>
                                                                <option>Price: Low to High</option>
                                                            </select>
                                                        </div>
                                                    </div> -->
                                                    <div class="col-md-6 col-md-offset-4">
                                                        <div class="searchbox">
                                                            <input type="text" class="form-control" placeholder="Search">
                                                            <span class="search-btn">
                                                            <i class="fa fa-search" aria-hidden="true"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <div class="delete row">
                                                            <a class="pointer" data-toggle="modal" data-target="#addPlaceModal"><img src="/assets/img/add-icon.png" class="img-responsive" alt="Add Icon" title="Add User"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>State</th>
                                                            <th>City</th>
                                                            <th>District</th>
                                                            <th class="text-center">Population</th>
                                                            <th class="text-center">Units</th>
                                                            <th class="text-center">Cost-assumption $<input type="text" value="50" class="form-control"></th>
                                                            <th class="text-center">Profit assumption $<input type="text" value="5" class="form-control"></th>
                                                            <th class="text-center">Sponsor</th>
                                                            <th>

                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="sponsors-body">
                                                        @foreach($places as $place)
                                                        <tr>
                                                            <td>{{$place->id}}</td>
                                                            <td>{{$state->name}}</td>
                                                            <td><input class="form-control sponsors-place-input-value" value="{{$place->city}}" data-field="city" data-place-id="{{$place->id}}"></td>
                                                            <td><input class="form-control sponsors-place-input-value" value="{{$place->district}}" data-field="district" data-place-id="{{$place->id}}"></td>
                                                            <td><input class="form-control sponsors-place-input-value" value="{{$place->population}}" data-field="population" data-place-id="{{$place->id}}"></td>
                                                            <td><input class="form-control sponsors-place-input-value" value="{{$place->units}}" data-field="units" data-place-id="{{$place->id}}"></td>
                                                            <td><input class="form-control sponsors-place-input-value" value="{{$place->cost_assumption}}" data-field="cost_assumption" data-place-id="{{$place->id}}"></td>
                                                            <td><input class="form-control sponsors-place-input-value" value="{{$place->profit_assumption}}" data-field="profit_assumption" data-place-id="{{$place->id}}"></td>
                                                            <td>
                                                                @if ($place->sponsor_name != null)
                                                                <span class="font-blue">{{$place->sponsor_name}}</span>
                                                                @else
                                                                    <span class="">Available</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {!! Form::open([
                                                                    'method'=>'DELETE',
                                                                    'url' => ['/admin/sponsors/places', $place->id],
                                                                    'style' => 'display:inline',
                                                                    'id' => 'delete-role'
                                                                ]) !!}
                                                                    {!! Form::image('/assets/img/delete-icon.png', 'Delete', ['class' => 'pull-right img-responsive remove-icon-link', 'style' => 'width: 25px;']) !!}
                                                                {!! Form::close() !!}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="pagination-block text-right">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="col-md-1 hidden-xs hidden-sm"></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog" aria-labelledby="addCountryModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Country</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              {!! Form::open(['url' => '/admin/sponsors/countries', 'class' => '', 'method' => 'post']) !!}
                <div class="form-group">
                    <label class="col-form-label">Country Name:</label>
                    <input type="text" class="form-control" name="name">
                </div>
              {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit-add-country-js">Add</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addStateModal" tabindex="-1" role="dialog" aria-labelledby="addStateModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add State</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              {!! Form::open(['url' => '/admin/sponsors/states', 'class' => '', 'method' => 'post']) !!}
                <div class="form-group">
                    <label class="col-form-label">State Name:</label>
                    <input type="text" class="form-control" name="name">
                    <input type="hidden" name="country_id" value="{{$country->id}}">
                </div>
              {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit-add-state-js">Add</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addPlaceModal" tabindex="-1" role="dialog" aria-labelledby="addPlaceModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Available Place</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              {!! Form::open(['url' => '/admin/sponsors/places', 'class' => '', 'method' => 'post']) !!}
                <input type="hidden" name="state_id" value="{{$state->id}}">
                <input type="hidden" name="country_id" value="{{$state->country_id}}">
                <div class="form-group">
                    <label class="col-form-label">City*:</label>
                    <input type="text" class="form-control" name="city" require>
                </div>
                <div class="form-group">
                    <label class="col-form-label">District*:</label>
                    <input type="text" class="form-control" name="district" require>
                </div>
                <div class="form-group">
                    <label class="col-form-label">Population:</label>
                    <input type="number" class="form-control" name="population" value="0">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Units:</label>
                    <input type="number" class="form-control" name="units" value="0">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Cost Assumption:</label>
                    <input type="number" class="form-control" name="cost_assumption" value="0">
                </div>
                <div class="form-group">
                    <label class="col-form-label">Profit Assumption:</label>
                    <input type="number" class="form-control" name="profit_assumption" value="0">
                </div>
              {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary submit-add-place-js">Add</button>
            </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    var BASE_URL = '{{ url('') }}' + '/api/v1/';
    // var BASEAPIMAIN = BASE + '/api/main';
</script>
<script src="/admin_/js/sponsor.js" type="text/javascript"></script>
@endsection