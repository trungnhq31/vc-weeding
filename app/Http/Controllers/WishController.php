<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Wedding\SubmitWishAction;
use App\Data\WishData;
use App\Http\Requests\Wedding\StoreWishRequest;
use Illuminate\Http\RedirectResponse;

class WishController extends Controller
{
    public function store(StoreWishRequest $request, SubmitWishAction $action): RedirectResponse
    {
        $dto = WishData::fromRequest($request->validated());
        $action->execute($dto);

        return back()->with('message', 'Lời chúc của bạn đã được gửi thành công!');
    }
}
