@extends('layouts.admin.app')

@section('title', translate('messages.about_us'))

@section('content')
    @php
        $aboutFieldMeta = [
            'about_hero_kicker' => ['label' => 'Hero kicker', 'type' => 'input'],
            'about_hero_title' => ['label' => 'Hero headline', 'type' => 'input'],
            'about_hero_subtitle' => ['label' => 'Hero subheadline', 'type' => 'textarea'],
            'about_hero_note_title' => ['label' => 'Hero image note title', 'type' => 'input'],
            'about_hero_note_text' => ['label' => 'Hero image note text', 'type' => 'textarea'],
            'about_story_kicker' => ['label' => 'Story kicker', 'type' => 'input'],
            'about_mission_label' => ['label' => 'Mission label', 'type' => 'input'],
            'about_mission' => ['label' => 'Mission', 'type' => 'textarea'],
            'about_vision_label' => ['label' => 'Vision label', 'type' => 'input'],
            'about_vision' => ['label' => 'Vision', 'type' => 'textarea'],
            'about_impact_kicker' => ['label' => 'Impact kicker', 'type' => 'input'],
            'about_impact_title' => ['label' => 'Impact section title', 'type' => 'input'],
            'about_impact_items' => ['label' => 'Impact cards', 'type' => 'textarea', 'hint' => 'One card per line. Format: Title|Description'],
            'about_values_kicker' => ['label' => 'Values kicker', 'type' => 'input'],
            'about_values_title' => ['label' => 'Values section title', 'type' => 'input'],
            'about_values_items' => ['label' => 'Value cards', 'type' => 'textarea', 'hint' => 'One card per line. Format: Title|Description'],
            'about_coverage_kicker' => ['label' => 'Coverage kicker', 'type' => 'input'],
            'about_coverage_title' => ['label' => 'Coverage title', 'type' => 'input'],
            'about_coverage_text' => ['label' => 'Coverage text', 'type' => 'textarea'],
            'about_coverage_tags' => ['label' => 'Coverage tags', 'type' => 'textarea', 'hint' => 'One tag per line.'],
            'about_coverage_map_title' => ['label' => 'Coverage map title', 'type' => 'input'],
            'about_coverage_map_text' => ['label' => 'Coverage map text', 'type' => 'input'],
            'about_milestones_kicker' => ['label' => 'Milestones kicker', 'type' => 'input'],
            'about_milestones_title' => ['label' => 'Milestones title', 'type' => 'input'],
            'about_milestones_items' => ['label' => 'Milestones', 'type' => 'textarea', 'hint' => 'One milestone per line.'],
            'about_team_kicker' => ['label' => 'Team kicker', 'type' => 'input'],
            'about_team_title' => ['label' => 'Team section title', 'type' => 'input'],
            'about_team_empty_title' => ['label' => 'Empty team title', 'type' => 'input'],
            'about_team_empty_text' => ['label' => 'Empty team text', 'type' => 'textarea'],
            'about_trust_kicker' => ['label' => 'Trust kicker', 'type' => 'input'],
            'about_trust_title' => ['label' => 'Trust section title', 'type' => 'input'],
            'about_trust_text' => ['label' => 'Trust/legal text', 'type' => 'textarea'],
            'about_privacy_cta' => ['label' => 'Privacy CTA label', 'type' => 'input'],
            'about_primary_cta' => ['label' => 'Primary CTA label', 'type' => 'input'],
            'about_secondary_cta' => ['label' => 'Secondary CTA label', 'type' => 'input'],
        ];

        $aboutValue = function ($key, $lang = 'default') use ($about_fields) {
            $setting = $about_fields[$key] ?? null;
            if ($lang === 'default') {
                return $setting?->getRawOriginal('value') ?? '';
            }

            if ($setting?->translations) {
                foreach ($setting->translations as $translation) {
                    if ($translation->locale === $lang && $translation->key === $key) {
                        return $translation->value;
                    }
                }
            }

            return '';
        };

        $settingTranslation = function ($setting, $key, $lang) {
            if ($setting?->translations) {
                foreach ($setting->translations as $translation) {
                    if ($translation->locale === $lang && $translation->key === $key) {
                        return $translation->value;
                    }
                }
            }

            return '';
        };
    @endphp

    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{ asset('public/assets/admin/img/privacy-policy.png') }}" class="w--26" alt="">
                </span>
                <span>{{ translate('messages.about_us') }}</span>
            </h1>
        </div>

        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{ route('admin.business-settings.about-us') }}" method="post" id="about_us-form" enctype="multipart/form-data">
                    @csrf

                    @php($language = \App\Models\BusinessSetting::where('key', 'language')->first())
                    @php($language = $language->value ?? null)

                    @if ($language)
                        <ul class="nav nav-tabs mb-4 border-0">
                            <li class="nav-item">
                                <a class="nav-link lang_link active" href="#" id="default-link">{{ translate('messages.default') }}</a>
                            </li>

                            @foreach (json_decode($language) as $lang)
                                <li class="nav-item">
                                    <a class="nav-link lang_link" href="#" id="{{ $lang }}-link">{{ \App\CentralLogics\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-0">About page content</h5>
                        </div>
                        <div class="card-body">
                            <div class="lang_form" id="default-form">
                                <div class="form-group">
                                    <label for="about_hero_image">Hero image</label>
                                    <input type="file" id="about_hero_image" name="about_hero_image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">This controls the main image on the About Us page. Recommended: wide photo, at least 900px wide.</small>
                                    @if(isset($about_fields['about_hero_image']) && $about_fields['about_hero_image']?->value)
                                        <div class="mt-2">
                                            <img src="{{ \App\CentralLogics\Helpers::get_full_url('about_hero_image', $about_fields['about_hero_image']->value, $about_fields['about_hero_image']->storage[0]?->value ?? 'public', 'aspect_1') }}" alt="About hero image" style="max-width: 220px; border-radius: 8px;">
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="about_title_default">{{ translate('messages.about_title') }} ({{ translate('messages.Default') }})</label>
                                    <input type="text" id="about_title_default" name="about_title[]" class="form-control" value="{{ $about_title?->getRawOriginal('value') ?? '' }}">
                                </div>

                                <div class="form-group">
                                    <label for="about_us_default">{{ translate('messages.about_us_description') }} ({{ translate('messages.Default') }})</label>
                                    <textarea id="about_us_default" class="ckeditor form-control" name="about_us[]">{!! $about_us?->getRawOriginal('value') ?? '' !!}</textarea>
                                    <small class="form-text text-muted">This appears in the Our Story section.</small>
                                </div>

                                <div class="row">
                                    @foreach($aboutFieldMeta as $key => $meta)
                                        <div class="col-md-{{ $meta['type'] === 'input' ? '6' : '12' }}">
                                            <div class="form-group">
                                                <label for="{{ $key }}_default">{{ $meta['label'] }} ({{ translate('messages.Default') }})</label>
                                                @if($meta['type'] === 'input')
                                                    <input type="text" id="{{ $key }}_default" name="{{ $key }}[]" class="form-control" value="{{ $aboutValue($key) }}">
                                                @else
                                                    <textarea id="{{ $key }}_default" name="{{ $key }}[]" class="form-control" rows="4">{{ $aboutValue($key) }}</textarea>
                                                @endif
                                                @if(isset($meta['hint']))
                                                    <small class="form-text text-muted">{{ $meta['hint'] }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="lang[]" value="default">
                            </div>

                            @if ($language)
                                @foreach(json_decode($language) as $lang)
                                    <div class="d-none lang_form" id="{{ $lang }}-form">
                                        <div class="form-group">
                                            <label for="about_title_{{ $lang }}">{{ translate('messages.about_title') }} ({{ $lang }})</label>
                                            <input type="text" id="about_title_{{ $lang }}" name="about_title[]" class="form-control" value="{{ $settingTranslation($about_title, 'about_title', $lang) }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="about_us_{{ $lang }}">{{ translate('messages.about_us_description') }} ({{ $lang }})</label>
                                            <textarea id="about_us_{{ $lang }}" class="ckeditor form-control" name="about_us[]">{!! $settingTranslation($about_us, 'about_us', $lang) !!}</textarea>
                                        </div>

                                        <div class="row">
                                            @foreach($aboutFieldMeta as $key => $meta)
                                                <div class="col-md-{{ $meta['type'] === 'input' ? '6' : '12' }}">
                                                    <div class="form-group">
                                                        <label for="{{ $key }}_{{ $lang }}">{{ $meta['label'] }} ({{ $lang }})</label>
                                                        @if($meta['type'] === 'input')
                                                            <input type="text" id="{{ $key }}_{{ $lang }}" name="{{ $key }}[]" class="form-control" value="{{ $aboutValue($key, $lang) }}">
                                                        @else
                                                            <textarea id="{{ $key }}_{{ $lang }}" name="{{ $key }}[]" class="form-control" rows="4">{{ $aboutValue($key, $lang) }}</textarea>
                                                        @endif
                                                        @if(isset($meta['hint']))
                                                            <small class="form-text text-muted">{{ $meta['hint'] }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="btn--container justify-content-end">
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/ckeditor/ckeditor.js') }}"></script>
@endpush
