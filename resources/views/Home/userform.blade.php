<?php $title_for_layout = __('Crona Database');?>
@extends('layouts.login.app')
@section('content')

<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-lg-12 col-12  layout-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-header">                                
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4>{{$questionnaire->name}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="widget-content widget-content-area">
                        <form method="POST" id="servayform" action="{{url('saveUserForm/'.$questionnaire->slug)}}" enctype="multipart/form-data">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">{{ __('User Info') }}</h1>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                First Name
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" name="data[user][first_name]" placeholder="first_name" required="">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                Last Name
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" name="data[user][last_name]" placeholder="last_name" required="">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                Email
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" name="data[user][email]" placeholder="email" >
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                Contact#
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" name="data[user][contact]" placeholder="mobile phone number" required="">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">{{ $questionnaire->name.__(' Form') }}</h1>
                    </div>
                    @csrf
                    <?php foreach($questions as $question){ ?>
                        <div class="form-group pt-3">
                            <label class="small mb-1"><b>Q.{{$question->statement}}?</b></label>
                            <?php if($question->type == 'text'){ ?>
                                <input type="text" required="" name="data[userform][{{$question->id}}]" class="form-control" value="">
                            <?php }else if($question->type == 'radio'){ ?>
                                    <div class="form-row">
                                    <?php foreach($question->questionAnswer as $answer){?>
                                        <div class="form-group">
                                            <input type="radio" required="" class="form-radio-input" name="data[userform][{{$question->id}}]" value="{{$answer->id}}">&nbsp;{{$answer->value}}&nbsp;&nbsp;
                                        </div>
                                    <?php } ?>
                                    </div>
                            <?php }else if($question->type == 'select'){?>
                                    <select class="form-control" required="" name="data[userform][{{$question->id}}]">
                                        <option value="">--Select Answer--</option>
                                        <?php foreach($question->questionAnswer as $answer){?>
                                            <option value="{{$answer->id}}">{{$answer->value}}</option>
                                        <?php } ?>
                                    </select>
                            <?php }else{?>
                                    <div class="form-row">
                                    <?php foreach($question->questionAnswer as $answer){?>
                                        <div class="form-group">
                                            <input type="checkbox" class="form-radio-input" name="data[userform][{{$question->id}}][]" value="{{$answer->id}}">&nbsp;{{$answer->value}} &nbsp;&nbsp;
                                        </div>
                                    <?php } ?>
                                    </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <input type="checkbox" required="" class="form-radio-input" name="iagree" id="iagree" value="1">&nbsp;&nbsp;&nbsp;&nbsp;{{__('i agree')}}&nbsp;&nbsp;
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">{{ __('Submit') }}</button>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php $pagename = str_replace('-','_',$questionnaire->name);
    $pagename = str_replace(' ','_',$pagename);?>
@section('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render=6Leyc9AZAAAAAGcJuZn4-5ZGw0IH5W-otJUgYqDA"></script>
    <script>
        grecaptcha.ready(function() {
            grecaptcha.execute('6Leyc9AZAAAAAGcJuZn4-5ZGw0IH5W-otJUgYqDA', {action: '<?php echo str_replace(' ','_',$pagename); ?>'}).then(function(token) {
                $('#servayform').prepend('<input type="hidden" name="captcha" value="' + token + '">'); 
            }); 
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
        });
    </script>
@endsection