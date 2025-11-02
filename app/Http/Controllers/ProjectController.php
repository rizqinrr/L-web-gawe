<?php

namespace App\Http\Controllers;

use id;
use App\Models\Tool;
use App\Models\Project;
use App\Models\Category;
use App\Models\ProjectTool;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreToolRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\StoreToolProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $user = Auth::user();
        $projectQuery = Project::with(['category', 'applicants'])->orderByDesc('id');

        if ($user->hasRole('project_client')) {
            $projectQuery->whereHas('owner', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            });
        }

        $projects = $projectQuery->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categorys = Category::all();
        return view('admin.projects.create', compact('categorys'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        //
        $user = Auth::user();
        $balance = $user->wallet->balance;

        if($request->input('budget') > $balance) {
            return redirect()->back()->withErrors(['budget' => 'Balance anda tidak cukup.']);
        }

        DB::transaction(function () use ($request, $user) {
            $user->wallet->decrement('balance', $request->input('budget'));

            $projectWalletTransaction = WalletTransaction::create([
                'type' => 'Prject Cost',
                'amount' => $request->input('budget'),
                'is_paid' => true,
                'user_id' => $user->id,
            ]);

            $validated = $request->validated();
            if($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validated['thumbnail'] = $thumbnailPath;
            }

            $validated['slug'] = Str::slug($validated['name']);
            $validated['has_finished'] = false;
            $validated['has_started'] = false;
            $validated['client_id'] = $user->id;

            $newProject = Project::create($validated);
        });

        return redirect()->route('admin.projects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
        return view('admin.projects.show', compact('project'));
    }

    public function tools_store(StoreToolProjectRequest $request, Project $project) {
        DB::transaction(function() use ($request, $project) {
            $validated = $request->validated();
            $validated['project_id'] = $project->id;

            $toolProject = ProjectTool::firstOrCreate($validated); //agar tidak duplikat
        });

        return redirect()->route('admin.project.tools', $project->id);

    }

    public function tools(Project $project) {

        if($project->client_id != auth()->id()){
            abort(403, 'Unauthorized action.');
        }
        $tools = Tool::all();
        return view('admin.projects.tools', compact('project', 'tools'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
