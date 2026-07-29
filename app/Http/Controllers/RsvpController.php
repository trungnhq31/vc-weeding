<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Wedding\SubmitRsvpAction;
use App\Data\RsvpData;
use App\Http\Requests\Wedding\StoreRsvpRequest;
use Illuminate\Http\RedirectResponse;

class RsvpController extends Controller
{
    public function store(StoreRsvpRequest $request, SubmitRsvpAction $action): RedirectResponse
    {
        $dto = RsvpData::fromRequest($request->validated());
        $action->execute($dto);

        return back()->with('message', 'Cảm ơn bạn đã phản hồi RSVP!');
    }
}
