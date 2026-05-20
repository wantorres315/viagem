<?php

namespace App\Http\Controllers;

use App\Models\ChecklistViagem;
use App\Models\Viagem;
use Illuminate\Http\Request;

class ChecklistViagemController extends Controller
{
    public function index(Request $request)
    {
        $viagemId = $request->input('viagem_id') ?? Viagem::first()?->id;
        $viagens = Viagem::all();
        $checklists = ChecklistViagem::where('viagem_id', $viagemId)->get();
        return view('pages.checklist.index', compact('checklists', 'viagens', 'viagemId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'viagem_id' => 'required|exists:viagens,id',
            'tarefa' => 'required|string|max:255',
        ]);
        ChecklistViagem::create($data);
        return back()->with('success', 'Tarefa adicionada!');
    }

    public function update(Request $request, $id)
    {
        $checklist = ChecklistViagem::findOrFail($id);
        $data = $request->validate([
            'tarefa' => 'required|string|max:255',
            'concluido' => 'nullable|boolean',
        ]);
        $checklist->update([
            'tarefa' => $data['tarefa'],
            'concluido' => $request->boolean('concluido'),
        ]);
        return back()->with('success', 'Tarefa atualizada!');
    }

    public function destroy($id)
    {
        $checklist = ChecklistViagem::findOrFail($id);
        $checklist->delete();
        return back()->with('success', 'Tarefa removida!');
    }

    // PATCH para dashboard (AJAX)
    public function toggleConcluido(Request $request, $id)
    {
        $item = ChecklistViagem::findOrFail($id);
        $item->concluido = !$item->concluido;
        $item->save();
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'concluido' => $item->concluido]);
        }
        return back();
    }
}
