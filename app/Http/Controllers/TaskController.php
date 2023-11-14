<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Http\Requests\{StoreTaskRequest, TaskUpdateRequest};

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sortBy = $request->input('sortBy', 'desc');
        $filterBy = '';
        $query = Task::with('user:id,name')->orderBy('created_at', $sortBy)->where('parent_id', null);

        if ($request->has('filterBy')) {
            $filterBy = $request->input('filterBy');
            $query = $query->where('title', 'like', "%{$filterBy}%");
        }

        return Inertia::render('Tasks/Index', [
            'tasks' => $query->get(),
            'sortBy' => $sortBy !== 'desc' ? $sortBy : '',
            'filterBy' => $filterBy,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->safe()->only(['title']));
        $parentId = $request->input('parent_id');

        if ($parentId) {
            $task->parent_id = $parentId;
            $task->save();
        }

        return redirect(route('tasks.show', $task->id));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $data = [
            'task' => $task->load('user:id,name', 'subTasks.user'),
        ];

        if($task->parent_id){
            $data['parent_url'] = route('tasks.show', $task->parent_id);
        }
        return Inertia::render('Tasks/View', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $validated = $request->validated();

        $task->title = $validated['title'];
        $task->body = $validated['body'];
        $task->status = $validated['status'];
        $task->save();

        return redirect(route('tasks.show', $task->id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // $this->authorize('delete', $task);
        $task->delete();
        return redirect(route('tasks.index'));
    }

    /**
     *
     */
    public function forceDestroy(Request $request)
    {
        Task::withTrashed()
        ->where('id', $request->route('task'))
        ->forceDelete();

        return redirect(route('tasks.trashed'));
    }

    /**
     *
     */
    public function attach(Request $request, Task $task)
    {
        $images=[];
        if($files=$request->file('images')){
            foreach($files as $file){
                // $name=$file->getClientOriginalName();
                $name= Str::random(10) . "." . $file->getClientOriginalExtension();
                $file->move('images',$name);
                $images[]=$name;
            }
        }

        $task->images = implode("|", array_merge(explode("|",$task->images), $images));
        $task->save();

        return redirect(route('tasks.show', $task->id));
    }

    /**
     *
     */
    public function trashed(Request $request)
    {
        $data = [
            'tasks' => Task::onlyTrashed()->with('user:id,name')->orderBy('created_at', 'desc')->where('parent_id', null)->get(),
        ];

        return Inertia::render('Tasks/Trashed', $data);
    }

}
