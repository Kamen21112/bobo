<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (Auth::check() && Auth::user()->role === 'client') {
                    abort(403, 'Нямате достъп до клиентската база данни.');
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function show(Client $client)
    {
        // Зареждаме колите на клиента и ремонтите към тях
        $client->load('cars.repairs');

        // Връщаме изглед (HTML страница), вместо сурови данни
        return view('clients.show', compact('client'));
    }

    // Показва формата за добавяне на нов клиент
    public function create()
    {
        return view('clients.create');
    }

    // Записва новия клиент в базата данни
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:clients,email',
            'notes' => 'nullable|string',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Клиентът е добавен успешно!');
    }
}
