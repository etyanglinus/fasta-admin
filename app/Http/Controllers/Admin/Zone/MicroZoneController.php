<?php

namespace App\Http\Controllers\Admin\Zone;

use App\Http\Controllers\Controller;
use App\Models\MicroZone;
use App\Models\Zone;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class MicroZoneController extends Controller
{
    public function index(Request $request): View
    {
        $zones = Zone::active()->get(['id', 'name']);
        $microZones = MicroZone::with('zone')
            ->when($request->zone_id, fn ($query) => $query->where('zone_id', $request->zone_id))
            ->latest()
            ->paginate(config('default_pagination'));

        return view('admin-views.zone.micro-zones', compact('zones', 'microZones'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:191',
            'description' => 'nullable|string|max:1000',
            'coordinates' => 'required|string',
            'status' => 'nullable|boolean',
        ]);

        $data['coordinates'] = $this->polygonFromCoordinates($data['coordinates']);
        if (! $this->polygonBelongsToZone($data['zone_id'], $data['coordinates'])) {
            Toastr::error(translate('Micro zone must be drawn inside the selected country zone'));
            return back()->withInput();
        }
        $data['status'] = $request->boolean('status');
        MicroZone::create($data);

        Toastr::success(translate('messages.micro_zone_created_successfully'));
        return back();
    }

    public function update(Request $request, MicroZone $microZone): RedirectResponse
    {
        $data = $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'name' => 'required|string|max:191',
            'description' => 'nullable|string|max:1000',
            'coordinates' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        if ($request->filled('coordinates')) {
            $data['coordinates'] = $this->polygonFromCoordinates($data['coordinates']);
            if (! $this->polygonBelongsToZone($data['zone_id'], $data['coordinates'])) {
                Toastr::error(translate('Micro zone must be drawn inside the selected country zone'));
                return back()->withInput();
            }
        } else {
            unset($data['coordinates']);
        }
        $data['status'] = $request->boolean('status');
        $microZone->update($data);

        Toastr::success(translate('messages.micro_zone_updated_successfully'));
        return back();
    }

    public function destroy(MicroZone $microZone): RedirectResponse
    {
        $microZone->delete();

        Toastr::success(translate('messages.micro_zone_deleted_successfully'));
        return back();
    }

    public function byZone(Request $request): JsonResponse
    {
        $microZones = MicroZone::active()
            ->where('zone_id', $request->zone_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($microZones);
    }

    public function getCoordinates(MicroZone $microZone): JsonResponse
    {
        if (! $microZone->coordinates) {
            return response()->json(['coordinates' => [], 'center' => null]);
        }

        $area = json_decode($microZone->coordinates[0]->toJson(), true);
        $coordinates = collect($area['coordinates'])->map(fn ($coordinate) => [
            'lat' => $coordinate[1],
            'lng' => $coordinate[0],
        ])->all();

        return response()->json(['coordinates' => $coordinates]);
    }

    private function polygonFromCoordinates(string $value): Polygon
    {
        $polygon = [];
        $lastCord = null;

        foreach (explode('),(', trim($value, '()')) as $index => $singleArray) {
            $coords = array_map('trim', explode(',', $singleArray));
            if (count($coords) < 2) {
                continue;
            }
            if ($index === 0) {
                $lastCord = $coords;
            }
            $polygon[] = new Point($coords[0], $coords[1]);
        }

        if ($lastCord) {
            $polygon[] = new Point($lastCord[0], $lastCord[1]);
        }

        return new Polygon([new LineString($polygon)]);
    }

    private function polygonBelongsToZone(int $zoneId, Polygon $polygon): bool
    {
        foreach ($polygon[0]->toArray()['coordinates'] as $coordinate) {
            $point = new Point($coordinate[1], $coordinate[0]);
            if (! Zone::where('id', $zoneId)->whereContains('coordinates', $point)->exists()) {
                return false;
            }
        }

        return true;
    }
}
