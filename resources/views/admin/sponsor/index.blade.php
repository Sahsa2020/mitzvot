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
                                <div class="col-md-10 text-center" style="width:auto">
                                    <div class="main-body" style="padding-top:100px">
                                        <h1 style="color:inherit">Manage your sponsors areas</h1>
                                        <h2>Please choose country you interested in</h2>
                                        <div class="clear20"></div>
                                        <div class="col-md-3 hidden-xs hidden-sm"></div>
                                        <div class="col-md-5">
                                            <div class="form-group form-group-border">
                                                <label>Country</label>
                                                <form >
                                                    <select id="country_selector" class="form-control" name="country">
                                                </from>
                                                </select>
                                            </div>
                                            <div class="option-box">
                                                <div class="option-head">
                                                    <div class="col-md-10">
                                                        <div class="checkbox">
                                                            <label>
                                                            <input type="checkbox" id="all-check">
                                                            <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span> Edit list of countries
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <div class="delete country">
                                                            <a><img src="/assets/img/delete-icon.png" class="img-responsive" alt="Delete Icon"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="option-list">
                                                    <div class="col-md-12">
                                                        <ul id="total_countries"></ul>
                                                    </div>
                                                </div>
                                                <div class="option-footer">
                                                    <a class="add-country">+</a>
                                                    <!-- <input type="file"> -->
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-4 text-left">
                                            <div class="row">
                                                <button type="submit" class="community-btn" style="margin:0px">Submit</button>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="col-md-12" id="state-body" hidden>
                                            <div class="select-state">
                                                <h3>Now you should select State</h3>
                                                <div class="row" id="state-div">
                                                    <div class="col-sm-2">
                                                        <a href="#" class="close">X</a>
                                                        <div class="state">Alabama</div>
                                                    </div>                                                    
                                                    <!-- <div class="col-sm-2 text-left">
                                                        <a href="#" class="add-country">+</a>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                        <div class="col-md-1 hidden-xs hidden-sm"></div>
                                        <div class="table-box col-md-10 box-display" style="visibility:  hidden;">
                                            <div class="table-head">
                                                <h3>Sponsors Table</h3>
                                                <div class="top-right">
                                                    <div class="col-md-4">
                                                        <div class="row">
                                                            <select class="form-control">
                                                                <option value="">Sort By</option>
                                                                <option>Price: Low to High</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="searchbox">
                                                            <input type="text" class="form-control" placeholder="Search">
                                                            <span class="search-btn">
                                                            <i class="fa fa-search" aria-hidden="true"></i></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-right">
                                                        <div class="delete row">
                                                            <a href="#"><img src="/assets/img/add-icon.png" class="img-responsive" alt="Add Icon" title="Add User"></a>
                                                            <a href="#"><img src="/assets/img/delete-icon.png" class="img-responsive" alt="Delete Icon" title="Delete Icon"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <div class="checkbox">
                                                                    <label>
                                                                    <input type="checkbox">
                                                                    <span class="cr"><i class="cr-icon fa fa-check" aria-hidden="true"></i></span>
                                                                    </label>
                                                                </div>
                                                            </th>
                                                            <th>#</th>
                                                            <th>City</th>
                                                            <th>District</th>
                                                            <th class="text-center">Population</th>
                                                            <th class="text-center">Units</th>
                                                            <th class="text-center">Cost-assumption $<input type="text" value="50" class="form-control"></th>
                                                            <th class="text-center">Profit assumption $<input type="text" value="5" class="form-control"></th>
                                                            <th class="text-center">Sponsor</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="sponsors-body">

                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- <div class="pagination-block text-right">
                                                <nav aria-label="Page navigation example">
                                                <ul class="pagination">
                                                    <li class="page-item"><a class="page-link" href="#">&lt; Prev</a></li>
                                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">Next ></a></li>
                                                </ul>
                                                </nav>
                                            </div> -->
                                        </div>
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
@endsection

@section('scripts')
<script>
    var BASE_URL = '{{ url('') }}' + '/api/v1/';
    // var BASEAPIMAIN = BASE + '/api/main';
</script>
<script src="/admin_/js/sponsor.js" type="text/javascript"></script>
@endsection