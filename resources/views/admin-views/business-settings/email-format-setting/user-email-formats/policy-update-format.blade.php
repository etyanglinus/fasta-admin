@extends('layouts.admin.app')

@section('title', translate('email_template'))

@push('css_or_js')
<link rel="stylesheet" href="{{asset('public/assets/admin/css/view-pages/email-templates.css')}}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center __gap-15px">
                <h1 class="page-header-title mr-3 mb-0">
                    <span class="page-header-icon">
                        <img src="{{ asset('public/assets/admin/img/email-setting.png') }}" class="w--26" alt="">
                    </span>
                    <span>{{ translate('messages.Email_Templates') }}</span>
                </h1>
                @include('admin-views.business-settings.email-format-setting.partials.email-template-options')
            </div>
            @include('admin-views.business-settings.email-format-setting.partials.user-email-template-setting-links')
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active">
                <div class="card mb-3">
                    @php($mail_status=\App\Models\BusinessSetting::where('key','policy_update_mail_status_user')->first()?->value ?? '0')
                    <div class="card-body">
                        <div class="maintenance-mode-toggle-bar d-flex flex-wrap justify-content-between border rounded align-items-center p-2">
                            <h5 class="text-capitalize m-0 text--primary pl-2">
                                {{ translate('Send_Mail_On_Policy_Update') }}
                                <span class="form-label-secondary text--primary" data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('Customers_will_receive_an_email_when_terms_and_conditions_or_privacy_policy_are_updated.') }}">
                                    <img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="">
                                </span>
                            </h5>
                            <label class="toggle-switch toggle-switch-sm">
                                <input type="checkbox" class="status toggle-switch-input dynamic-checkbox"
                                       data-id="mail-status"
                                       data-type="status"
                                       data-image-on='{{asset('/public/assets/admin/img/modal')}}/place-order-on.png'
                                       data-image-off="{{asset('/public/assets/admin/img/modal')}}/place-order-off.png"
                                       data-title-on="{{translate('Want_to_enable_policy_update_mail?')}}"
                                       data-title-off="{{translate('Want_to_disable_policy_update_mail?')}}"
                                       data-text-on="<p>{{translate('If_enabled,_customers_will_receive_an_email_when_terms_or_privacy_policy_changes.')}}</p>"
                                       data-text-off="<p>{{translate('If_disabled,_customers_will_not_receive_policy_update_emails.')}}</p>"
                                       id="mail-status" {{$mail_status == '1'?'checked':''}}>
                                <span class="toggle-switch-label text mb-0">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                        <form action="{{route('admin.business-settings.email-status',['user','policy-update',$mail_status == '1'?0:1])}}" method="get" id="mail-status_form"></form>
                    </div>
                </div>

                @php($data=\App\Models\EmailTemplate::where('type','user')->where('email_type', 'policy_update')->first())
                @php($template= $template ?? $data?->email_template ?? 5)
                <form action="{{ route('admin.business-settings.email-setup-update', ['user','policy-update']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="email-format-wrapper">
                                <div class="left-content">
                                    <div class="d-inline-block">
                                        @include('admin-views.business-settings.email-format-setting.partials.email-template-section')
                                    </div>
                                    <div class="card">
                                        <div class="card-body">
                                            @include('admin-views.business-settings.email-format-setting.templates.email-format-'.$template)
                                        </div>
                                    </div>
                                </div>
                                <div class="right-content">
                                    <div class="d-flex flex-wrap justify-content-between __gap-15px mt-2 mb-5">
                                        @php($data=\App\Models\EmailTemplate::withoutGlobalScope('translate')->with('translations')->where('type','user')->where('email_type', 'policy_update')->first())
                                        @php($language=\App\Models\BusinessSetting::where('key','language')->first())
                                        @php($language = $language->value ?? null)
                                        @if($language)
                                            <ul class="nav nav-tabs m-0 border-0">
                                                <li class="nav-item"><a class="nav-link lang_link active" href="#" id="default-link">{{translate('messages.default')}}</a></li>
                                                @foreach (json_decode($language) as $lang)
                                                    <li class="nav-item"><a class="nav-link lang_link" href="#" id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a></li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <div class="d-flex justify-content-end">
                                            <div class="text--primary-2 py-1 d-flex flex-wrap align-items-center py-1" type="button" data-toggle="modal" data-target="#instructions">
                                                <strong class="mr-2">{{translate('Read_Instructions')}}</strong>
                                                <div class="blinkings"><i class="tio-info-outined"></i></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="card-title mb-3">{{translate('Icon')}}</h5>
                                        <label class="custom-file">
                                            <input type="file" name="icon" id="mail-icon" class="custom-file-input" accept=".webp, .jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <span class="custom-file-label">{{ translate('messages.Choose_File') }}</span>
                                        </label>
                                    </div>
                                    <br>

                                    <div>
                                        <h5 class="card-title mb-3"><img src="{{asset('public/assets/admin/img/pointer.png')}}" class="mr-2" alt="">{{translate('Header_Content')}}</h5>
                                        @if ($language)
                                            <div class="__bg-F8F9FC-card default-form lang_form" id="default-form">
                                                <div class="form-group">
                                                    <label class="form-label">{{translate('Main_Title')}}({{ translate('messages.default') }})</label>
                                                    <input type="text" name="title[]" value="{{ $data?->getRawOriginal('title') }}" data-id="mail-title" placeholder="Policy update from {company_name}" class="form-control">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="form-label">{{ translate('Mail_Body_Message') }}({{ translate('messages.default') }})</label>
                                                    <textarea class="form-control" id="ckeditor" data-id="mail-body" name="body[]">{!! $data?->getRawOriginal('body') !!}</textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                            @foreach(json_decode($language) as $lang)
                                                @php($translate = [])
                                                @if($data && count($data['translations']))
                                                    @foreach($data['translations'] as $t)
                                                        @if($t->locale == $lang && $t->key == 'title') @php($translate[$lang]['title'] = $t->value) @endif
                                                        @if($t->locale == $lang && $t->key == 'body') @php($translate[$lang]['body'] = $t->value) @endif
                                                    @endforeach
                                                @endif
                                                <div class="__bg-F8F9FC-card d-none lang_form" id="{{$lang}}-form">
                                                    <div class="form-group">
                                                        <label class="form-label">{{translate('Main_Title')}}({{strtoupper($lang)}})</label>
                                                        <input type="text" name="title[]" placeholder="Policy update from {company_name}" class="form-control" value="{{$translate[$lang]['title']??''}}">
                                                    </div>
                                                    <div class="form-group mb-0">
                                                        <label class="form-label">{{ translate('Mail_Body_Message') }}({{strtoupper($lang)}})</label>
                                                        <textarea class="ckeditor form-control" name="body[]">{!! $translate[$lang]['body']??'' !!}</textarea>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="lang[]" value="{{$lang}}">
                                            @endforeach
                                        @else
                                            <div class="__bg-F8F9FC-card default-form">
                                                <div class="form-group">
                                                    <label class="form-label">{{translate('Main_Title')}}</label>
                                                    <input type="text" name="title[]" value="{{ $data?->getRawOriginal('title') }}" placeholder="Policy update from {company_name}" class="form-control">
                                                </div>
                                                <div class="form-group mb-0">
                                                    <label class="form-label">{{ translate('Mail_Body_Message') }}</label>
                                                    <textarea class="ckeditor form-control" name="body[]">{!! $data?->getRawOriginal('body') !!}</textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="lang[]" value="default">
                                        @endif
                                    </div>
                                    <br>

                                    <div>
                                        <h5 class="card-title mb-3"><img src="{{asset('public/assets/admin/img/pointer.png')}}" class="mr-2" alt="">{{translate('Button_Content')}}</h5>
                                        <div class="__bg-F8F9FC-card">
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label class="form-label text-capitalize">{{translate('Button_Name')}}</label>
                                                    <input type="text" data-id="mail-button" name="button_name[]" placeholder="Review policy" class="form-control h--45px" value="{{ $data?->getRawOriginal('button_name') }}">
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="form-label text-capitalize">{{translate('Button_URL')}}</label>
                                                    <input type="text" name="button_url" class="form-control h--45px" value="{{ $data?->button_url }}" placeholder="{{ url('/terms-and-conditions') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    <div>
                                        <h5 class="card-title mb-3"><img src="{{asset('public/assets/admin/img/pointer.png')}}" class="mr-2" alt="">{{translate('Footer_Content')}}</h5>
                                        <div class="__bg-F8F9FC-card">
                                            <div class="form-group">
                                                <label class="form-label">{{translate('Footer_Text')}}</label>
                                                <input type="text" name="footer_text[]" class="form-control" value="{{ $data?->getRawOriginal('footer_text') }}" placeholder="Thank you for staying informed.">
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="form-label">{{translate('Copyright_Text')}}</label>
                                                <input type="text" name="copyright_text[]" class="form-control" value="{{ $data?->getRawOriginal('copyright_text') }}" placeholder="{company_name}">
                                            @if($language)
                                                @foreach(json_decode($language) as $lang)
                                                    <input type="hidden" name="button_name[]" value="{{ $data?->getRawOriginal('button_name') ?? 'Review policy' }}">
                                                    <input type="hidden" name="footer_text[]" value="{{ $data?->getRawOriginal('footer_text') ?? 'Thank you for staying informed.' }}">
                                                    <input type="hidden" name="copyright_text[]" value="{{ $data?->getRawOriginal('copyright_text') ?? '{company_name}' }}">
                                                @endforeach
                                            @endif
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="email_template" value="{{ $template }}">
                                    <div class="btn--container justify-content-end mt-4">
                                        <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                                        <button type="submit" class="btn btn--primary">{{translate('messages.save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    @include('admin-views.business-settings.email-format-setting.partials.email-template-instructions')
    <script src="{{asset('public/assets/admin/js/view-pages/email-templates.js')}}"></script>
@endpush
