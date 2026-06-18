@extends('layouts.admin.app')

@section('title', translate('Cities'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">{{ translate('Cities') }}</h1>
        </div>

        <div class="alert alert-soft-info mb-3">
            <strong>{{ translate('How this works') }}:</strong>
            {{ translate('Countries contain cities. Draw each city boundary so customers in one city cannot order from stores assigned to another city.') }}
        </div>

        <div class="row g-3">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ translate('Add City') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.business-settings.zone.micro-zones.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="input-label">{{ translate('Country') }}</label>
                                <select name="zone_id" id="micro-zone-country" class="form-control js-select2-custom" required>
                                    <option value="">{{ translate('Select country') }}</option>
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{ translate('City Name') }}</label>
                                <input type="text" name="name" class="form-control" placeholder="{{ translate('Example: Nairobi, Mombasa, Kisumu') }}" required maxlength="191">
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{ translate('Description') }}</label>
                                <textarea name="description" class="form-control" rows="3" maxlength="1000"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="input-label">{{ translate('Draw City Boundary') }}</label>
                                <p class="fs-12 text-muted mb-2">{{ translate('Select the country first, search the city, then draw the city service area inside the country outline.') }}</p>
                                <textarea name="coordinates" id="micro-zone-coordinates" class="form-control d-none" required readonly></textarea>
                                <input id="micro-zone-search" class="controls rounded" type="text" placeholder="{{ translate('Search city') }}" />
                                <div id="micro-zone-map" class="rounded border" style="height: 360px;"></div>
                            </div>
                            <label class="form-check form--check mb-3">
                                <input type="checkbox" name="status" value="1" class="form-check-input" checked>
                                <span class="form-check-label">{{ translate('Active') }}</span>
                            </label>
                            <button class="btn btn--primary" type="submit">{{ translate('messages.submit') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h5 class="mb-0">{{ translate('City List') }}</h5>
                        <form class="d-flex gap-2">
                            <select name="zone_id" class="form-control js-select2-custom" onchange="this.form.submit()">
                                <option value="">{{ translate('messages.all_countries') }}</option>
                                @foreach($zones as $zone)
                                    <option value="{{ $zone->id }}" {{ request('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered table-align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('messages.sl') }}</th>
                                    <th>{{ translate('messages.country') }}</th>
                                    <th>{{ translate('messages.city') }}</th>
                                    <th>{{ translate('messages.modules') }}</th>
                                    <th>{{ translate('Stores') }}</th>
                                    <th>{{ translate('Deliverymen') }}</th>
                                    <th>{{ translate('Coverage') }}</th>
                                    <th>{{ translate('messages.status') }}</th>
                                    <th class="text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($microZones as $key => $microZone)
                                    <tr>
                                        <td>{{ $key + $microZones->firstItem() }}</td>
                                        <td>{{ $microZone->zone?->name }}</td>
                                        <td>
                                            <strong>{{ $microZone->name }}</strong>
                                            @if($microZone->description)
                                                <div class="fs-12 text-muted">{{ $microZone->description }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @forelse($microZone->modules as $module)
                                                <span class="badge badge-soft-info mb-1">{{ $module->module_name }}</span>
                                            @empty
                                                <span class="text-muted">{{ translate('No modules enabled') }}</span>
                                            @endforelse
                                            <div class="fs-12 text-muted mt-1">
                                                {{ $microZone->modules_count }} {{ translate('enabled') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-secondary">{{ $microZone->stores_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-soft-secondary">{{ $microZone->deliverymen_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $microZone->coordinates ? 'badge-soft-success' : 'badge-soft-warning' }}">
                                                {{ $microZone->coordinates ? translate('Drawn') : translate('Needs map') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $microZone->status ? 'badge-soft-success' : 'badge-soft-danger' }}">
                                                {{ $microZone->status ? translate('Active') : translate('Inactive') }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a href="{{ route('admin.business-settings.zone.module-setup', [$microZone->zone_id, 'micro_zone_id' => $microZone->id]) }}"
                                                   class="btn btn-sm btn-outline-theme-dark action-btn"
                                                   data-toggle="tooltip"
                                                   data-placement="bottom"
                                                   data-original-title="{{ translate('Assign modules') }}">
                                                    <i class="tio-apps"></i>
                                                </a>
                                                <a href="{{ route('admin.business-settings.zone.surge-price.list', [$microZone->zone_id, 'micro_zone_id' => $microZone->id]) }}"
                                                   class="btn btn-sm btn-outline-theme-light action-btn"
                                                   data-toggle="tooltip"
                                                   data-placement="bottom"
                                                   data-original-title="{{ translate('Surge prices') }}">
                                                    <i class="tio-trending-up"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn--primary btn-outline-primary action-btn" data-toggle="modal" data-target="#edit-micro-zone-{{ $microZone->id }}">
                                                    <i class="tio-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.business-settings.zone.micro-zones.destroy', $microZone) }}" method="post">
                                                    @csrf
                                                    @method('delete')
                                                    <button type="submit" class="btn btn-sm btn--danger btn-outline-danger action-btn">
                                                        <i class="tio-delete-outlined"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="edit-micro-zone-{{ $microZone->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.business-settings.zone.micro-zones.update', $microZone) }}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">{{ translate('Edit City') }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label class="input-label">{{ translate('messages.country') }}</label>
                                                            <select name="zone_id" class="form-control" required>
                                                                @foreach($zones as $zone)
                                                                    <option value="{{ $zone->id }}" {{ $microZone->zone_id == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="input-label">{{ translate('City Name') }}</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $microZone->name }}" required maxlength="191">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="input-label">{{ translate('Description') }}</label>
                                                            <textarea name="description" class="form-control" rows="3" maxlength="1000">{{ $microZone->description }}</textarea>
                                                        </div>
                                                        @if($microZone->coordinates)
                                                            <textarea name="coordinates" class="d-none">@foreach($microZone->coordinates[0]->toArray()['coordinates'] as $key=>$coords)<?php if(count($microZone->coordinates[0]->toArray()['coordinates']) != $key+1) {if($key != 0) echo(','); ?>({{$coords[1]}}, {{$coords[0]}})<?php } ?>@endforeach</textarea>
                                                        @endif
                                                        <label class="form-check form--check">
                                                            <input type="checkbox" name="status" value="1" class="form-check-input" {{ $microZone->status ? 'checked' : '' }}>
                                                            <span class="form-check-label">{{ translate('Active') }}</span>
                                                        </label>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn--primary" type="submit">{{ translate('messages.update') }}</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                        @if($microZones->count() === 0)
                            <div class="empty--data">
                                <img src="{{ asset('/public/assets/admin/svg/illustrations/sorry.svg') }}" alt="">
                                <h5>{{ translate('no_data_found') }}</h5>
                            </div>
                        @endif
                    </div>
                    <div class="page-area px-4 pb-3 d-flex justify-content-end">
                        {{ $microZones->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    "use strict";

    let microZoneMap;
    let microZoneDrawingManager;
    let microZonePolygon = null;
    let parentZonePolygon = null;

    function initializeMicroZoneMap() {
        @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
        @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
        const defaultCenter = {
            lat: {{$default_location ? $default_location['lat'] : '-1.286389'}},
            lng: {{$default_location ? $default_location['lng'] : '36.817223'}}
        };
        const mapId = "{{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}";

        microZoneMap = new google.maps.Map(document.getElementById("micro-zone-map"), {
            zoom: 10,
            center: defaultCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapId: mapId
        });

        microZoneDrawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
            },
            polygonOptions: {
                editable: true,
                strokeColor: "#159947",
                fillColor: "#159947",
                fillOpacity: 0.18,
                strokeWeight: 2
            }
        });
        microZoneDrawingManager.setMap(microZoneMap);

        google.maps.event.addListener(microZoneDrawingManager, "overlaycomplete", function (event) {
            if (microZonePolygon) {
                microZonePolygon.setMap(null);
            }
            microZonePolygon = event.overlay;
            setMicroZoneCoordinates(microZonePolygon);
            google.maps.event.addListener(microZonePolygon.getPath(), "set_at", function () {
                setMicroZoneCoordinates(microZonePolygon);
            });
            google.maps.event.addListener(microZonePolygon.getPath(), "insert_at", function () {
                setMicroZoneCoordinates(microZonePolygon);
            });
        });

        const input = document.getElementById("micro-zone-search");
        const searchBox = new google.maps.places.SearchBox(input);
        microZoneMap.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
        microZoneMap.addListener("bounds_changed", function () {
            searchBox.setBounds(microZoneMap.getBounds());
        });
        searchBox.addListener("places_changed", function () {
            const places = searchBox.getPlaces();
            if (!places.length) {
                return;
            }
            const bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (!place.geometry || !place.geometry.location) {
                    return;
                }
                if (place.geometry.viewport) {
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            microZoneMap.fitBounds(bounds);
        });
    }

    function setMicroZoneCoordinates(polygon) {
        $('#micro-zone-coordinates').val(polygon.getPath().getArray());
    }

    function loadParentZone(zoneId) {
        if (!zoneId || !microZoneMap) {
            return;
        }

        $.get({
            url: '{{ url('/admin/zone/get-coordinates') }}/' + zoneId,
            dataType: 'json',
            success: function (data) {
                if (parentZonePolygon) {
                    parentZonePolygon.setMap(null);
                }
                parentZonePolygon = new google.maps.Polygon({
                    paths: data.coordinates,
                    strokeColor: "#D94727",
                    strokeOpacity: 0.9,
                    strokeWeight: 2,
                    fillColor: "#D94727",
                    fillOpacity: 0.08
                });
                parentZonePolygon.setMap(microZoneMap);

                const bounds = new google.maps.LatLngBounds();
                data.coordinates.forEach(function (point) {
                    bounds.extend(point);
                });
                microZoneMap.fitBounds(bounds);
            }
        });
    }

    $(document).on('ready', function () {
        $('.js-select2-custom').each(function () {
            $.HSCore.components.HSSelect2.init($(this));
        });

        $('#micro-zone-country').on('change', function () {
            $('#micro-zone-coordinates').val('');
            if (microZonePolygon) {
                microZonePolygon.setMap(null);
                microZonePolygon = null;
            }
            loadParentZone(this.value);
        });
    });
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&callback=initializeMicroZoneMap&libraries=drawing,places,marker&v=3.62"></script>
@endpush


