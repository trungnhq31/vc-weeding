<?php

declare(strict_types=1);

namespace App\Modules\GroundedAI\Services;

use Illuminate\Support\Facades\Http;

final class OpenAiAgentService
{
    /**
     * @param  array<string, mixed>  $metrics
     * @param  array<int, array{role: string, content: string}>  $chatHistory
     */
    public function generateResponse(string $userPrompt, array $metrics, array $chatHistory = []): ?string
    {
        $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY');

        if (empty($apiKey)) {
            return null;
        }

        $model = config('services.openai.model') ?? env('OPENAI_MODEL', 'gpt-4o-mini');

        $systemPrompt = "Bạn là Trợ lý AI Chuyên gia Lập Kế hoạch Đám Cưới Việt Nam của hệ thống Eloria OS (Operating System for Wedding Planning).\n"
            ."Bạn am hiểu sâu sắc các phong tục tập quán đám cưới Việt Nam (Lễ Dạm Ngõ, Lễ Ăn Hỏi/Bê Tráp, Lễ Gia Tiên, Lễ Hằng Thuận tại Chùa, Thánh Lễ Hôn Phối tại Nhà Thờ, Tiệc Cưới Nhà Hàng).\n"
            ."Nhiệm vụ của bạn là giải đáp thắc mắc, phân tích kế hoạch và tư vấn cho Dâu Rể/Wedding Planner bằng tiếng Việt thân thiện, chuyên nghiệp.\n\n"
            ."QUY TẮC BẮT BUỘC (ZERO HALLUCINATION):\n"
            ."1. Bạn CHỈ ĐƯỢC phép đưa ra con số, chi phí, tiến độ, nhà cung cấp dựa trên DỮ LIỆU THỰC TẾ dưới đây:\n"
            .json_encode($metrics, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."\n\n"
            ."2. Nếu dữ liệu không có thông tin khách hỏi, hãy nói rõ dữ liệu chưa cập nhật và hướng dẫn khách mở trang tương ứng để điền.\n"
            .'3. Luôn giữ phong cách giao tiếp ấm áp, lãng mạn nhưng ngắn gọn, mạch lạc.';

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        foreach ($chatHistory as $msg) {
            if (isset($msg['role'], $msg['content'])) {
                $messages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => (string) $msg['content'],
                ];
            }
        }

        $messages[] = ['role' => 'user', 'content' => $userPrompt];

        try {
            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.4,
                    'max_tokens' => 800,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return $data['choices'][0]['message']['content'] ?? null;
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }
}
