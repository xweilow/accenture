<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Event::query()->paginate(10);

        return response()->json([
            'code' => 200,
            'data' => EventResource::collection($data),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total' => $data->count(),
                'per_page' => $data->perPage(),
                'last_page' => $data->lastPage(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $event = Event::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
        ]);

        return response()->json([
            'code' => 200,
            'data' => new EventResource($event),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return response()->json([
            'code' => 200,
            'data' => new EventResource($event),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->name = $request->name;
        $event->slug = Str::slug($request->name);
        $event->start_at = $request->start_at;
        $event->end_at = $request->end_at;
        $event->save();

        return response()->json([
            'code' => 200,
            'data' => new EventResource($event),
        ]);
    }

    public function patch(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        if($request->has('name')) {
            $event->slug = Str::slug($request->name);
            $event->save();
        }

        $event->update($request->all());

        return response()->json([
            'code' => 200,
            'data' => new EventResource($event),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->deleted_at = Carbon::now();
        $event->save();

        return response()->json([
            'code' => 200,
            'data' => new EventResource($event),
        ]);
    }

    public function getActiveEvents()
    {
        $data = Event::where('start_at', '<=', Carbon::now())
            ->where('end_at', '>', Carbon::now())
            ->where('deleted_at', NULL)
            ->paginate(10);

        return response()->json([
            'code' => 200,
            'data' => EventResource::collection($data),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total' => $data->count(),
                'per_page' => $data->perPage(),
                'last_page' => $data->lastPage(),
            ],
        ]);
    }
}
