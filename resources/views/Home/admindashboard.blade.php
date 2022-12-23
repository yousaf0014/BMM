@extends('layouts.admin.app')
@section('content')

<div class="container-fluid">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table mr-1"></i>
            User 
        </div>
        <div class="card-body">
            @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }} You are logged in!
                    </div>
                @endif

                

                <div class="box-typical box-typical-padding">
                        
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label" style="color: #000000;font-weight: bold;">Name:</label>
                        <div class="col-sm-10" style="color: #000000;">
                            {{$user->first_name.' '.$user->last_name}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 form-control-label" style="color: #000000;font-weight: bold;">Email:</label>
                        <div class="col-sm-10" style="color: #000000;">
                            {{$user->email}}
                        </div>
                    </div>
                    {{ Html::ul($errors->all()) }}
                        {!! Form::open(array('url' => 'actAs','id'=>'actAs','name'=>'actAs','class'=>'form-horizontal')) !!}
                        <div class="form-group mb-4">
                            <label for="formGroupExampleInput">Client</label>
                            <select name="user_id" class="form-control input-sm">
                                <option value="">--Select Client--</option>
                                  <?php
                                    foreach($clients as $usr){ ?>
                                    <option value="{{$usr->id}}">{{$usr->first_name.' '.$usr->last_name}}</option>
                                  <?php } ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm">Act</button>
                    {!! Form::close() !!}
                </div>
        </div>
    </div>
</div>
@endsection
