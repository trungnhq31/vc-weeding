<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

test('no RSVP acronym is displayed in user-facing invitation template copy', function () {
    $templateFiles = File::files(resource_path('js/Components/Wedding/Templates'));

    foreach ($templateFiles as $file) {
        $content = $file->getContents();

        // Remove script tags to check template UI copy only
        $templateBody = preg_replace('/<script[\s\S]*?<\/script>/i', '', $content);

        // Assert that raw RSVP acronym is not rendered in template copy labels/titles
        // Exception: variable names like rsvpAttending or v-model="rsvpNotes" are allowed in Vue tags
        $cleanCopy = preg_replace('/:[a-zA-Z0-9_-]+="[^"]*"/i', '', $templateBody);
        $cleanCopy = preg_replace('/v-[a-zA-Z0-9_-]+="[^"]*"/i', '', $cleanCopy);
        $cleanCopy = preg_replace('/@[a-zA-Z0-9_-]+="[^"]*"/i', '', $cleanCopy);

        expect($cleanCopy)->not->toContain('RSVP (');
        expect($cleanCopy)->not->toContain('Xác Nhận Tham Dự (RSVP)');
        expect($cleanCopy)->not->toContain('Gửi Lời Xác Nhận RSVP');
    }
});

test('public invitation templates comply with bright pastel theme rules', function () {
    $templateFiles = File::files(resource_path('js/Components/Wedding/Templates'));

    foreach ($templateFiles as $file) {
        $content = $file->getContents();

        // Check root element or outer container backgrounds
        expect($content)->not->toContain('bg-slate-950');
        expect($content)->not->toContain('bg-emerald-950');
    }
});
