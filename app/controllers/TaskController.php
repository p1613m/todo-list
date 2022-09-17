<?php

namespace App\Controllers;

use App\Core\Abstracts\Controller;
use App\Core\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public ?string $layout = 'layouts/default';

    /**
     * @param Request $request
     * @return string
     */
    public function list(Request $request): string
    {
        $perPage = 3;
        $currentPage = $request->get('page', 1);
        $totalTasks = Task::query()->count();
        $orderColumn = $request->get('order_column');
        $orderSort = $request->get('order_sort');

        $taskBuilder = Task::query()->limit(3)->offset($currentPage * $perPage - $perPage);

        if (in_array($orderColumn, ['username', 'email', 'is_completed']) && in_array($orderSort, ['asc', 'desc'])) {
            $taskBuilder->orderBy($orderColumn, $orderSort);
        } else {
            $taskBuilder->orderBy('id', 'desc');
        }

        $totalPages = ceil($totalTasks / $perPage);

        $tasks = $taskBuilder->get();

        return $this->view('index', compact(
        'tasks',
      'currentPage',
                'totalPages',
                'orderColumn',
                'orderSort',
                'totalTasks'
        ));
    }

    /**
     * Store a task
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): void
    {
        $validated = $request->validate([
            'username' => ['required', 'max' => 255],
            'email' => ['email', 'required'],
            'text' => ['required'],
        ]);

        Task::create($validated);

        $this->session->setFlash('success', 1);
        $this->redirect('home');
    }

    /**
     * Update task text
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
    {
        if($task = Task::query()->findById($request->get('task_id'))) {
            $editedText = $request->get('edited_text');

            if ($editedText && $editedText != $task->text) {
                $task->update([
                    'is_edited' => 1,
                    'text' => $editedText,
                ]);
            }
        }

        $this->redirect('home');
    }

    /**
     * Set completed task
     *
     * @param Request $request
     * @return void
     */
    public function setCompleted(Request $request): void
    {
        if($task = Task::query()->findById($request->get('task_id'))) {
            $task->update([
                'is_completed' => 1,
            ]);
        }

        $this->redirect('home');
    }
}