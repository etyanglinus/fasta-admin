@extends('layouts.admin.app')

@section('title', translate('messages.react_site_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-sm-0">
                    <h1 class="page-header-title">{{translate('React Site Setup')}}</h1>
                </div>
            </div>
        </div>
        @php($react_setup=\App\Models\BusinessSetting::where(['key'=>'react_setup'])->first())
        @php($react_setup=$react_setup?json_decode($react_setup->value, true):null)

        <div class="card">
            <div class="card-body">
                <form action="{{getEnvMode()!='demo'?route('admin.business-settings.react-update'):'javascript:'}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="react_license_code" class="form-label text-capitalize">{{translate('React access code (optional)')}}</label>
                                <input id="react_license_code" type="text" placeholder="activated" class="form-control h--45px" name="react_license_code"
                                    value="{{getEnvMode()!='demo'?($react_setup['react_license_code'] ?? 'activated'):''}}">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="react_domain" class="form-label text-capitalize">{{translate('React Domain')}}</label>
                                <input id="react_domain" type="text" placeholder="{{request()->getHost()}}" class="form-control h--45px" name="react_domain"
                                    value="{{getEnvMode()!='demo'?($react_setup['react_domain'] ?? request()->getHost()):''}}">
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="{{getEnvMode()!='demo'?'submit':'button'}}"  class="btn btn--primary mb-2 call-demo">{{translate('messages.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush