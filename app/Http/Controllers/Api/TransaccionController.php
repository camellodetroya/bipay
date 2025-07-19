<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaccion;
use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaccionController extends Controller
{

    public function export()
    {
        $fileName = 'transacciones.csv';

        $transacciones = Transaccion::with(['emisor', 'receptor'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $columns = ['ID', 'Emisor', 'Receptor', 'Monto', 'Fecha'];

        $callback = function () use ($transacciones, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transacciones as $transaccion) {
                fputcsv($file, [
                    $transaccion->id,
                    $transaccion->emisor->name ?? 'N/A',
                    $transaccion->receptor->name ?? 'N/A',
                    number_format($transaccion->monto, 2, ',', '.'),
                    $transaccion->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $emisor = \Illuminate\Support\Facades\Auth::user();

        
        $validated = $request->validate([
            'receptor_id' => 'required|exists:users,id',
            'monto' => 'required|numeric|min:1',
        ]);



        // no tranferible a uno mismo
        if ($emisor->id == $validated['receptor_id']) {
            return response()->json(['message' => 'No puedes transferirte a ti mismo.'], 400);
        }

        // Verifica el total transferido hoy, hasta 5000
        $totalTransferidoHoy = Transaccion::where('emisor_id', $emisor->id)
            ->whereDate('created_at', now()->toDateString())
            ->sum('monto');

        $montoActual = $validated['monto'];

        if (($totalTransferidoHoy + $montoActual) > 5000) {
            return response()->json([
                'message' => 'Has alcanzado el límite diario de $5.000 en transferencias'
            ], 429);
        }



        // Verificar saldo suficiente
        if ($emisor->saldo_inicial < $validated['monto']) {
            return response()->json(['message' => 'Saldo insuficiente.'], 400);
        }
        
        DB::transaction(function () use ($emisor, $validated) {
            $receptor = User::findOrFail($validated['receptor_id']);
            $monto = $validated['monto'];

            $emisor->saldo_inicial -= $monto;
            $emisor->save();

            $receptor->saldo_inicial += $monto;
            $receptor->save();

            Transaccion::create([
                'emisor_id' => $emisor->id,
                'receptor_id' => $receptor->id,
                'monto' => $monto,
            ]);
        });

        return response()->json(['message' => 'Transferencia realizada con éxito']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
