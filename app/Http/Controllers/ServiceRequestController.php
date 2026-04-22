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
            $requests = ServiceRequest::with(['car'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif ($user->role === 'mechanic' || $user->role === 'admin') {
            $requests = ServiceRequest::with(['user', 'car'])
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            abort(403);
        }

        // Групиране по дата
        $groupedRequests = $requests->groupBy(function ($req) {
            return $req->created_at->format('d.m.Y');
        });

        return view('service_requests.index', compact('requests', 'groupedRequests'));
    }

    public function create()
    {
        if (Auth::user()->role !== 'client') {
            abort(403, 'Само клиенти могат да създават нови заявки.');
        }
        $cars = Auth::user()->cars;
        return view('service_requests.create', compact('cars'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'client') {
            abort(403);
        }

        $validatedData = $request->validate([
            'car_id'      => 'required|exists:cars,id',
            'description' => 'required|string',
        ]);

        $car = Auth::user()->cars()->where('id', $validatedData['car_id'])->first();
        if (!$car) {
            abort(403);
        }

        ServiceRequest::create([
            'user_id'     => Auth::id(),
            'car_id'      => $car->id,
            'description' => $validatedData['description'],
            'status'      => 'pending',
        ]);

        return redirect()->route('service_requests.index')->with('success', 'Заявката беше изпратена успешно!');
    }

    public function approve(ServiceRequest $serviceRequest)
    {
        if (Auth::user()->role !== 'mechanic' && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $serviceRequest->update(['status' => 'approved']);
        $serviceRequest->load('car');

        \App\Models\Repair::create([
            'title'       => 'Заявка: ' . ($serviceRequest->car->make ?? '') . ' ' . ($serviceRequest->car->model ?? ''),
            'description' => $serviceRequest->description,
            'car_id'      => $serviceRequest->car_id,
            'mechanic_id' => Auth::id(),
            'status'      => 'in_progress',
            'claimed_at'  => now(),
        ]);

        return redirect()->back()->with('success', 'Заявката е одобрена и преместена в ремонти!');
    }

    /**
     * Polling endpoint – връща брой чакащи заявки + хеш на последната промяна.
     * Използва се от JS на клиента за live обновяване без reload.
     */
    public function poll()
    {
        $user = Auth::user();

        if ($user->role === 'client') {
            $query = ServiceRequest::where('user_id', $user->id);
        } else {
            $query = ServiceRequest::where('status', 'pending');
        }

        $count  = $query->count();
        $latest = $query->max('updated_at');

        return response()->json([
            'count'  => $count,
            'hash'   => md5($count . $latest),
        ]);
    }
}
