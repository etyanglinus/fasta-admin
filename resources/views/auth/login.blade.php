<!DOCTYPE html>
<?php

    $log_email_succ = session()->get('log_email_succ');
?>

<html dir="{{ $site_direction }}" lang="{{ $locale }}" class="{{ $site_direction === 'rtl'?'active':'' }}">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{translate('messages.login')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('public/favicon.ico')}}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/theme.minc619.css?v=1.0')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">
    <style>
        :root {
            --fasta-login-primary: #039d55;
            --fasta-login-ink: #15332a;
            --fasta-login-muted: #6b7f78;
            --fasta-login-line: #dce8e2;
            --fasta-login-soft: #f4faf7;
        }

        body {
            background:
                radial-gradient(circle at 15% 18%, rgba(3, 157, 85, 0.13), transparent 28%),
                linear-gradient(135deg, #eef7f2 0%, #ffffff 45%, #f3f8f5 100%);
        }

        .auth-wrapper {
            min-height: 100vh;
            padding: 28px;
            gap: 28px;
        }

        .auth-wrapper-left {
            border-radius: 28px;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(8, 65, 46, 0.88), rgba(3, 157, 85, 0.72)),
                url({{ asset('public/assets/admin/css/images/auth-bg.png') }}) no-repeat center/cover;
            box-shadow: 0 24px 70px rgba(21, 51, 42, 0.16);
            min-height: calc(100vh - 56px);
        }

        .auth-wrapper-left .auth-left-cont {
            margin-inline-start: 0;
            margin-inline-end: 0;
            max-width: 560px;
            padding: 48px;
            color: #fff;
        }

        .auth-wrapper-left .auth-left-cont img {
            background: rgba(255, 255, 255, 0.92);
            border-radius: 18px;
            padding: 12px 16px;
            max-width: 210px;
            height: 76px;
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.16);
        }

        .auth-wrapper-left .auth-left-cont .title {
            color: #fff;
            font-size: 56px;
            letter-spacing: 0;
            max-width: 640px;
        }

        .auth-wrapper-left .auth-left-cont .title .text--039D55 {
            color: #dff8eb !important;
        }

        .auth-wrapper-right {
            max-width: 560px;
            min-height: calc(100vh - 56px);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(220, 232, 226, 0.9);
            box-shadow: 0 24px 70px rgba(21, 51, 42, 0.14);
            padding: 34px;
            position: relative;
            align-content: center;
        }

        .auth-wrapper-right .__login-badge {
            position: absolute;
            top: 24px;
            right: 24px;
            border-radius: 999px;
            background: var(--fasta-login-soft);
            color: var(--fasta-login-primary);
            border: 1px solid rgba(3, 157, 85, 0.18);
            padding: 9px 13px;
            font-weight: 600;
        }

        .auth-wrapper-right .auth-wrapper-form {
            max-width: 430px;
            padding: 24px 0 6px;
        }

        .auth-wrapper-right .auth-header {
            color: var(--fasta-login-muted);
            margin-bottom: 32px;
            font-size: 15px;
            font-weight: 400;
        }

        .auth-wrapper-right .auth-header .title {
            color: var(--fasta-login-ink);
            font-size: 34px;
            line-height: 1.15;
            margin-bottom: 8px;
        }

        .auth-wrapper-right .input-label {
            color: var(--fasta-login-ink);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .auth-wrapper-right .form-control,
        .auth-wrapper-right .input-group-text {
            height: 52px !important;
            border-radius: 14px;
            border-color: var(--fasta-login-line);
            background: #fbfdfc;
            color: var(--fasta-login-ink);
        }

        .auth-wrapper-right .input-group-merge .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .auth-wrapper-right .input-group-merge .input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .auth-wrapper-right .form-control:focus {
            border-color: rgba(3, 157, 85, 0.55);
            box-shadow: 0 0 0 4px rgba(3, 157, 85, 0.11);
            background: #fff;
        }

        .auth-wrapper-right .custom-control-label,
        .auth-wrapper-right .text-primary {
            color: var(--fasta-login-muted) !important;
        }

        .auth-wrapper-right .text-hover--primary:hover {
            color: var(--fasta-login-primary) !important;
        }

        .auth-wrapper-right .btn--primary {
            height: 52px !important;
            border-radius: 14px;
            background: var(--fasta-login-primary);
            border-color: var(--fasta-login-primary);
            box-shadow: 0 14px 26px rgba(3, 157, 85, 0.22);
            font-weight: 700;
        }

        .auth-wrapper-right .btn--primary:hover {
            filter: brightness(0.94);
            box-shadow: 0 16px 30px rgba(3, 157, 85, 0.28);
        }

        .auth-wrapper-right .auto-fill-data-copy {
            margin-top: 22px;
            padding: 16px;
            border: 1px solid var(--fasta-login-line);
            border-radius: 16px;
            background: var(--fasta-login-soft);
            color: var(--fasta-login-muted);
        }

        .auth-wrapper-right .auto-fill-data-copy .action-btn {
            border-radius: 12px;
        }

        .modal-content {
            border-radius: 22px;
            border: 0;
            box-shadow: 0 24px 70px rgba(21, 51, 42, 0.18);
        }

        .close-modal-icon {
            border-radius: 12px;
        }

        @media (min-width: 1550px) {
            .auth-wrapper-right .auth-wrapper-form {
                transform: none;
            }
            .auth-wrapper-right .btn-block,
            .auth-wrapper-right .form-control {
                border-radius: 14px;
                height: 52px !important;
            }
        }

        @media (max-width: 1300px) {
            .auth-wrapper-left .auth-left-cont .title {
                font-size: 42px;
            }
            .auth-wrapper-right .auth-header .title {
                font-size: 30px;
            }
        }

        @media (max-width: 991px) {
            .auth-wrapper {
                padding: 14px;
            }
            .auth-wrapper-right {
                border-radius: 22px;
                min-height: calc(100vh - 28px);
                padding: 26px 20px;
            }
            .auth-wrapper-right .__login-badge {
                position: static;
                margin: 0 auto 18px;
            }
        }
    </style>
</head>

<body>
<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main">
    <div class="auth-wrapper">
        <div class="auth-wrapper-left">
            <div class="auth-left-cont">
                @php($store_logo = \App\Models\BusinessSetting::where(['key' => 'logo'])->first())
                <img class="onerror-image"  data-onerror-image="{{asset('/public/assets/admin/img/favicon.png')}}"
                src="{{\App\CentralLogics\Helpers::get_full_url('business', $store_logo?->value?? '', $store_logo?->storage[0]?->value ?? 'public','favicon')}}"  alt="public/img">
                <h2 class="title">{{translate('Your')}} <span class="d-block">{{translate('All Service')}}</span> <strong class="text--039D55">{{translate('in one field')}}....</strong></h2>
            </div>
        </div>
        <div class="auth-wrapper-right">
            <!-- Card -->
            <div class="auth-wrapper-form">
                <div class="d-sm-none flex-grow-1 mb-2">
                    <img class="w-50px img-fluid" class="onerror-image"  data-onerror-image="{{asset('/public/assets/admin/img/favicon.png')}}"
                        src="{{\App\CentralLogics\Helpers::get_full_url('business', $store_logo?->value?? '', $store_logo?->storage[0]?->value ?? 'public','favicon')}}"  alt="public/img">
                </div>
                <!-- Form -->
                <form class="" action="{{route('login_post')}}" method="post" id="form-id">
                    @csrf
                    <input type="hidden" name="role" value="{{  $role ?? null }}">
                    <div class="auth-header">
                        <div class="mb-5">
                            <h2 class="title">{{ translate($role) }} {{translate('messages.login')}}</h2>
                            <div>{{translate('messages.welcome_back_login_to_your_panel') }}.</div>
                        </div>
                    </div>

                    <!-- Form Group -->
                    <div class="js-form-message form-group">
                        <label class="input-label text-capitalize" for="signinSrEmail">{{translate('messages.your_email')}}</label>

                        <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                tabindex="1" placeholder="email@address.com" value="{{ $email ?? '' }}" aria-label="email@address.com"
                                required data-msg="{{ translate('Please_enter_a_valid_email_address.') }}">
                    </div>
                    <!-- End Form Group -->

                    <!-- Form Group -->
                    <div class="js-form-message form-group mb-2">
                        <label class="input-label" for="signupSrPassword" tabindex="0">
                            <span class="d-flex justify-content-between align-items-center">
                                {{translate('messages.password')}}
                            </span>
                        </label>

                        <div class="input-group input-group-merge">
                            <input type="password" class="js-toggle-password form-control form-control-lg"
                                    name="password" id="signupSrPassword" placeholder="{{translate('messages.password_length_placeholder',['length'=>'6+'])}}" value="{{ $password ?? '' }}"
                                    aria-label="{{translate('messages.password_length_placeholder',['length'=>'6+'])}}" required
                                    data-msg="{{translate('messages.invalid_password_warning')}}"
                                    data-hs-toggle-password-options='{
                                                "target": "#changePassTarget",
                                    "defaultClass": "tio-hidden-outlined",
                                    "showClass": "tio-visible-outlined",
                                    "classChangeTarget": "#changePassIcon"
                                    }'>
                            <div id="changePassTarget" class="input-group-append">
                                <a class="input-group-text" href="javascript:">
                                    <i id="changePassIcon" class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Form Group -->

                    <div class="d-flex justify-content-between mt-5">
                        <!-- Checkbox -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="termsCheckbox" {{ $password ? 'checked' : '' }}
                                        name="remember">
                                <label class="custom-control-label text-muted" for="termsCheckbox">
                                    {{translate('messages.remember_me')}}
                                </label>
                            </div>
                        </div>
                        <!-- End Checkbox -->
                        <!-- forget password -->
                        <div class="form-group" id="forget-password" style="display: {{ $role == 'admin' ? '' : 'none' }};">
                            <div class="custom-control">
                                <span type="button" data-toggle="modal" class="text-primary text-hover--primary" data-target="#forgetPassModal">{{ translate('Forget Password') }}?</span>
                            </div>
                        </div>
                        <!-- End forget password -->
                        <div class="form-group" id="forget-password1" style="display: {{ $role == 'vendor' ? '' : 'none' }};">
                            <div class="custom-control">
                                <span type="button" data-toggle="modal" class="text-primary text-hover--primary" data-target="#forgetPassModal1">{{ translate('messages.Forget Password') }}?</span>
                            </div>
                        </div>
                        <!-- End forget password -->
                    </div>

                    @include('admin-views.partials._recaptcha')

                    <button type="submit" class="btn btn-lg btn-block btn--primary mt-xxl-3" id="signInBtn">{{translate('messages.login')}}</button>
                </form>
                <!-- End Form -->
                @if(getEnvMode() == 'demo')
                @if (isset($role) && $role == 'admin')
                <div class="auto-fill-data-copy">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <span class="d-block"><strong>Email</strong> : admin@admin.com</span>
                            <span class="d-block"><strong>Password</strong> : 12345678</span>
                        </div>
                        <div>
                            <button class="btn action-btn btn--primary m-0 copy_cred"><i class="tio-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                @if (isset($role) && $role == 'vendor')
                <div class="auto-fill-data-copy">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <span class="d-block"><strong>Email</strong> : test.restaurant@gmail.com</span>
                            <span class="d-block"><strong>Password</strong> : 12345678</span>
                        </div>
                        <div>
                            <button class="btn action-btn btn--primary m-0 copy_cred2"><i class="tio-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
            <!-- End Card -->

        </div>
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->
<div class="modal fade" id="forgetPassModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header justify-content-end">
        <span type="button" class="close-modal-icon" data-dismiss="modal">
            <i class="tio-clear"></i>
        </span>
      </div>
      <div class="modal-body">
        <div class="forget-pass-content">
            <img src="{{asset('/public/assets/admin/img/send-mail.svg')}}" alt="">
            <!-- After Succeed -->
            <h4>
                {{ translate('Send_Mail_to_Your_Email') }} ?
            </h4>
            <p>
                {{ translate('A mail will be send to your registered email') }} {{ isset($role) && $role == 'admin'  ? \App\Models\Admin::where('role_id',1)->first()?->masked_email : ''  }} {{ translate('with a  link to change passowrd') }}
            </p>
            <a class="btn btn-lg btn-block btn--primary mt-3" href="{{route('reset-password')}}">
                {{ translate('Send Mail') }}
            </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="forgetPassModal1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header justify-content-end">
        <span type="button" class="close-modal-icon" data-dismiss="modal">
            <i class="tio-clear"></i>
        </span>
      </div>
      <div class="modal-body">
        <div class="forget-pass-content">
            <img src="{{asset('/public/assets/admin/img/send-mail.svg')}}" alt="">
            <!-- After Succeed -->
            <!-- <img src="{{asset('/public/assets/admin/img/sent-mail.svg')}}" alt=""> -->
            <h4>
                {{ translate('messages.Send_Mail_to_Your_Email') }} ?
            </h4>
            <form class="" action="{{ route('vendor-reset-password') }}" method="post">
                @csrf

                <input type="email" name="email" id="" class="form-control" placeholder="{{ translate('messages.plesae_enter_your_registerd_email') }}" required>
                <button type="submit" class="btn btn-lg btn-block btn--primary mt-3">{{ translate('messages.Send Mail') }}</button>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="successMailModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header justify-content-end">
          <span type="button" class="close-modal-icon" data-dismiss="modal">
              <i class="tio-clear"></i>
          </span>
        </div>
        <div class="modal-body">
          <div class="forget-pass-content">
              <!-- After Succeed -->
              <img src="{{asset('/public/assets/admin/img/sent-mail.svg')}}" alt="">
              <h4>
                {{ translate('A mail has been sent to your registered email') }}!
              </h4>
              <p>
                {{ translate('Click the link in the mail description to change password') }}
              </p>
              <button class="btn btn-lg btn-block btn--primary mt-3" data-dismiss="modal">
                {{ translate('Got_It') }}
              </button>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        "use strict";
        @foreach($errors->all() as $error)
        toastr.error('{{translate($error)}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
@if ($log_email_succ)
@php(session()->forget('log_email_succ'))
    <script>
        "use strict";
        $('#successMailModal').modal('show');
    </script>
@endif

<script>
    "use strict";
    // $("#forget-password").hide();
        $("#role-select").change(function() {
            var selectValue = $(this).val();
            if (selectValue == "admin") {
            $("#forget-password").show();
            $("#forget-password1").hide();
            } else if(selectValue == "vendor") {
            $("#forget-password").hide();
            $("#forget-password1").show();
            }
            else {
            $("#forget-password").hide();
            $("#forget-password1").hide();
            }
        });

    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
    $(document).ready(function() {
        $('.onerror-image').on('error', function() {
            let img = $(this).data('onerror-image')
            $(this).attr('src', img);
        });
    });
</script>

<script>
    $(document).on('click', '.reloadCaptcha', function () {
        $.ajax({
            url: "{{ route('reload-captcha') }}",
            type: "GET",
            dataType: 'json',
            beforeSend: function () {
                $('#loading').show()
                $('.capcha-spin').addClass('active')
            },
            success: function (data) {
                $('#reload-captcha').html(data.view);
            },
            complete: function () {
                $('#loading').hide()
                $('.capcha-spin').removeClass('active')
            }
        });
    });

</script>

@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script src="https://www.google.com/recaptcha/api.js?render={{$recaptcha['site_key']}}"></script>
@endif
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script>
        $(document).ready(function () {
            $('#signInBtn').click(function (e) {
                if ($('#set_default_captcha_value').val() == 1) {
                    $('#form-id').submit();
                    return true;
                }
                e.preventDefault();
                if (typeof grecaptcha === 'undefined') {
                    toastr.error('Invalid recaptcha key provided. Please check the recaptcha configuration.');
                    $('#reload-captcha').removeClass('d-none');
                    $('#set_default_captcha_value').val('1');

                    return;
                }
                grecaptcha.ready(function () {
                    grecaptcha.execute('{{$recaptcha['site_key']}}', { action: 'submit' }).then(function (token) {
                        $('#g-recaptcha-response').val(token);
                        $('#form-id').submit();
                    });
                });
                window.onerror = function (message) {
                    var errorMessage = 'An unexpected error occurred. Please check the recaptcha configuration';
                    if (message.includes('Invalid site key')) {
                        errorMessage = 'Invalid site key provided. Please check the recaptcha configuration.';
                    } else if (message.includes('not loaded in api.js')) {
                        errorMessage = 'reCAPTCHA API could not be loaded. Please check the recaptcha API configuration.';
                    }
                    $('#reload-captcha').removeClass('d-none');
                    $('#set_default_captcha_value').val('1');
                    toastr.error(errorMessage)
                    return true;
                };
            });
        });
    </script>
@endif
{{-- recaptcha scripts end --}}




@if(getEnvMode()=='demo')
    <script>
        "use strict";
        $('.copy_cred').on('click', function () {
            $('#signinSrEmail').val('admin@admin.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        })
        $('.copy_cred2').on('click', function () {
            $('#signinSrEmail').val('test.restaurant@gmail.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        })
    </script>
@endif

<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public//assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
