<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    private function rules(string $kind): array
    {
        return [
            'title'  => 'required|string|max:255',
            'date'   => 'required|date',
            'time'   => 'nullable|date_format:H:i',
            'place'  => 'nullable|string|max:255',
            'notes'  => 'nullable|string',
            'budget' => $kind === 'event' ? 'nullable|numeric' : 'prohibited',
        ];
    }
 
    private function own(Request $r, string $kind, Agenda $item): void
    {
        abort_unless($item->user_id === $r->user()->id && $item->kind === $kind, 403);
    }
 
    public function index(string $kind)
    {
        return Agenda::mine()->where('kind', $kind)
            ->orderBy('date')->orderBy('time')->get();
    }
 
    public function store(Request $r, string $kind)
    {
        $data = $r->validate($this->rules($kind));
 
        $item = $r->user()->Agendas()->create($data + [
            'kind'   => $kind,
            'status' => 'upcoming',
        ]);
 
        return response()->json($item, 201);
    }
 
    public function update(Request $r, string $kind, Agenda $item)
    {
        $this->own($r, $kind, $item);
        $item->update($r->validate($this->rules($kind)));
 
        return $item;
    }
 
    public function toggleDone(Request $r, string $kind, Agenda $item)
    {
        $this->own($r, $kind, $item);
        $item->update(['status' => $item->status === 'done' ? 'upcoming' : 'done']);
 
        return $item;
    }
 
    public function destroy(Request $r, string $kind, Agenda $item)
    {
        $this->own($r, $kind, $item);
        $item->delete();
 
        return response()->noContent();
    }
 
    public function clear(string $kind)
    {
        Agenda::mine()->where('kind', $kind)->delete();
 
        return response()->noContent();
    }
}
