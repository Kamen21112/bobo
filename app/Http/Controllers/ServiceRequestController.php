<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'client') {
            $requests = ServiceRequest::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else if ($user->role === 'mechanic' || $user->role === 'admin') {
            // Mechanics and admins see all requests
            $requests = ServiceRequest::with('user')->orderBy('created_at', 'desc')->get();
        } else {
            abort(403, 'Нямате достъп до тази страница.');
        }

        return view('service_requests.index', compact('requests'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'client') {
            abort(403, 'Само клиенти могат да създават нови заявки.');
        }

        return view('service_requests.create');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'client') {
            abort(403, 'Нямате права за това действие.');
        }

        $validatedData = $request->validate([
            'car_make' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        ServiceRequest::create([
            'user_id' => Auth::id(),
            'car_make' => $validatedData['car_make'],
            'car_model' => $validatedData['car_model'],
            'description' => $validatedData['description'],
            'status' => 'pending'
        ]);

        return redirect()->route('service_requests.index')->with('success', 'Заявката беше изпратена успешно!');
    }

    public function approve(ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'mechanic' && Auth::user()->role !== 'admin') {
            abort(403, 'Нямате права да одобрявате заявки.');
        }

        $serviceRequest->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Заявката е одобрена успешно! Можете да се свържете с клиента или да въведете данните ръчно.');
    }
}
