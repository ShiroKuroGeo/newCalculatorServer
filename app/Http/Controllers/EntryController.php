<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;

class EntryController extends Controller
{

    private function rules()
    {
        return [
            'label' => 'required|string|max:225',
            'amount' => 'required|numeric',
            'status' => 'required|in:record,wishlist',
            'date' => 'required|date',
        ];
    }

    private function respo(int $status, string $message, $data)
    {
        return response()->json([
            'message' => $status === 501 ? 'Server Unavailable. Please try again later.' : $message,
            'data' => $data ?? [],
        ], $status);
    }

    private function own(Request $request, Entry $entry)
    {
        abort_unless($entry->user_id === $request->user()->id, 403);
    }

    public function index()
    {
        return Entry::mine()->orderByDesc('date')->orderByDesc('id')->get();
    }

    public function store(Request $request)
    {
        try {
            $entry = $request->user()->entries()->create($request->validate($this->rules()));

            return $this->respo(200, 'Successfully stored entry.', $entry);
        } catch (\Throwable $th) {
            return $this->respo(501, '', []);
        }
    }

    public function show(Request $r, Entry $entry)
    {
        try {
            $this->own($r, $entry);

            return $this->respo(200, 'Successfully stored entry.', $entry);
        } catch (\Throwable $th) {
            return $this->respo(501, '', []);
        }
    }

    public function update(Request $r, Entry $entry)
    {
        try {
            $this->own($r, $entry);
            $entry->update($r->validate($this->rules()));

            return $this->respo(200, 'Successfully stored entry.', $entry);
        } catch (\Throwable $th) {
            return $this->respo(501, '', []);
        }
    }

    public function toggleStatus(Request $r, Entry $entry)
    {
        try {
            $this->own($r, $entry);
            $entry->update(['status' => $entry->status === 'record' ? 'wishlist' : 'record']);

            return $this->respo(200, $entry->status === 'record' ? 'switch wishlist' : 'switch record', $entry);
        } catch (\Throwable $th) {
            return $this->respo(501, '', []);
        }
    }

    public function destroy(Request $r, Entry $entry)
    {
        try {
            $this->own($r, $entry);
            $entry->delete();

            return $this->respo(200, 'Successfully delete entry.', []);
        } catch (\Throwable $th) {
            return $this->respo(501, '', []);
        }
    }

    public function clear()
    {
        try {
            Entry::mine()->delete();

            return $this->respo(200, 'Cleared entry.', []);
        } catch (\Throwable $th) {
            return $this->respo(501, '', []);
        }
    }
}
