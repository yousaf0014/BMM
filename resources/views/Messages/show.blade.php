@extends('layouts.admin.app')
<!-- if there are creation errors, they will show here -->
@section('content')
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <h6 class="">Message Details</h6>
                            
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-12  layout-spacing">
                <div id="searchCompanies" >
                    <div class="statbox widget box box-shadow">
                        <div class="widget-header">                                
                            <div class="row">
                                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                    <h4>Details</h4>
                                </div>     
                            </div>
                        </div>
                        <div class="widget-content widget-content-area">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput"><strong>Title</strong></label>
                                        <div>
                                            {{$ad->title}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput"><strong>Zone</strong></label>
                                        <div>
                                            {{$zone->name}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput"><strong>Beacon</strong></label>
                                        <div>
                                            {{$beacon->unique_id}}  
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="form-group">
                                        <label for="formGroupExampleInput"><strong>Message</strong></label>
                                        <div>
                                            {{$ad->message}} 
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-9">
                                    <a href="{{ url('messages') }}" class="btn btn-danger btn-icon-split">
                                        <span class="icon text-white-50">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                        </span>
                                        <span class="text">Go Back</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
  
@endsection