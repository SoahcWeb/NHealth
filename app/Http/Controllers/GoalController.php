<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Goal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    /**
     * Display the authenticated user's goals.
     */
    public function index(Request $request): View
    {
        return view('nhealth.goals.index', [
            'goals' => $request->user()->goals()->latest()->paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new goal.
     */
    public function create(Request $request): View
    {
        return view('nhealth.goals.create', [
            'goal' => new Goal([
                'status' => 'active',
            ]),
            'activeGoalsCount' => $request->user()->activeGoals()->count(),
        ]);
    }

    /**
     * Store a newly created goal for the authenticated user.
     */
    public function store(StoreGoalRequest $request): RedirectResponse
    {
        $request->user()->goals()->create($request->validated());

        return redirect()
            ->route('nhealth.goals.index')
            ->with('status', 'Goal created.');
    }

    /**
     * Show the form for editing one of the authenticated user's goals.
     */
    public function edit(Request $request, Goal $goal): View
    {
        return view('nhealth.goals.edit', [
            'goal' => $this->findUserGoalOrFail($request, $goal),
        ]);
    }

    /**
     * Update one of the authenticated user's goals.
     */
    public function update(UpdateGoalRequest $request, Goal $goal): RedirectResponse
    {
        $goal = $this->findUserGoalOrFail($request, $goal);
        $goal->update($request->validated());

        return redirect()
            ->route('nhealth.goals.index')
            ->with('status', 'Goal updated.');
    }

    /**
     * Remove one of the authenticated user's goals.
     */
    public function destroy(Request $request, Goal $goal): RedirectResponse
    {
        $this->findUserGoalOrFail($request, $goal)->delete();

        return redirect()
            ->route('nhealth.goals.index')
            ->with('status', 'Goal deleted.');
    }

    /**
     * Resolve a goal only within the authenticated user's scope.
     */
    private function findUserGoalOrFail(Request $request, Goal $goal): Goal
    {
        return $request->user()->goals()
            ->whereKey($goal->getKey())
            ->firstOrFail();
    }
}
