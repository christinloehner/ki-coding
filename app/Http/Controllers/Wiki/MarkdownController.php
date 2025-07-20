<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use App\Services\MarkdownService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class MarkdownController extends Controller
{
    private MarkdownService $markdownService;

    public function __construct(MarkdownService $markdownService)
    {
        $this->markdownService = $markdownService;
    }

    /**
     * Preview markdown content.
     */
    public function preview(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'markdown' => 'required|string|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 400);
        }

        $markdown = $request->input('markdown');
        
        // Validate markdown content
        $validationErrors = $this->markdownService->validate($markdown);
        if (!empty($validationErrors)) {
            return response()->json([
                'error' => 'Markdown validation failed',
                'messages' => $validationErrors,
            ], 400);
        }

        try {
            $html = $this->markdownService->preview($markdown);
            $plainText = $this->markdownService->toPlainText($markdown);
            $readingTime = $this->markdownService->calculateReadingTime($markdown);
            $headings = $this->markdownService->extractHeadings($markdown);
            $excerpt = $this->markdownService->generateExcerpt($markdown);

            return response()->json([
                'html' => $html,
                'plain_text' => $plainText,
                'reading_time' => $readingTime,
                'headings' => $headings,
                'excerpt' => $excerpt,
                'word_count' => str_word_count($plainText),
                'character_count' => mb_strlen($markdown),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Markdown conversion failed',
                'message' => 'Unable to convert markdown to HTML',
            ], 500);
        }
    }

    /**
     * Generate table of contents for markdown.
     */
    public function tableOfContents(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'markdown' => 'required|string|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 400);
        }

        $markdown = $request->input('markdown');
        
        try {
            $headings = $this->markdownService->extractHeadings($markdown);
            $markdownWithToc = $this->markdownService->addTableOfContents($markdown);

            return response()->json([
                'headings' => $headings,
                'markdown_with_toc' => $markdownWithToc,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'TOC generation failed',
                'message' => 'Unable to generate table of contents',
            ], 500);
        }
    }

    /**
     * Validate markdown content.
     */
    public function validate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'markdown' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 400);
        }

        $markdown = $request->input('markdown');
        
        try {
            $errors = $this->markdownService->validate($markdown);
            $isValid = empty($errors);
            
            $response = [
                'valid' => $isValid,
                'errors' => $errors,
            ];
            
            if ($isValid) {
                $response['stats'] = [
                    'character_count' => mb_strlen($markdown),
                    'word_count' => str_word_count($this->markdownService->toPlainText($markdown)),
                    'reading_time' => $this->markdownService->calculateReadingTime($markdown),
                    'heading_count' => count($this->markdownService->extractHeadings($markdown)),
                ];
            }

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'Unable to validate markdown',
            ], 500);
        }
    }

    /**
     * Get markdown statistics.
     */
    public function stats(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'markdown' => 'required|string|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 400);
        }

        $markdown = $request->input('markdown');
        
        try {
            $plainText = $this->markdownService->toPlainText($markdown);
            $headings = $this->markdownService->extractHeadings($markdown);
            
            // Count different elements
            $lines = explode("\n", $markdown);
            $codeBlocks = preg_match_all('/```/', $markdown) / 2;
            $links = preg_match_all('/\[([^\]]+)\]\(([^)]+)\)/', $markdown);
            $images = preg_match_all('/!\[([^\]]*)\]\(([^)]+)\)/', $markdown);
            $tables = preg_match_all('/\|/', $markdown);
            $lists = preg_match_all('/^[\s]*[\-\*\+][\s]/', $markdown, $matches, PREG_MULTILINE);
            $numberedLists = preg_match_all('/^[\s]*\d+\.[\s]/', $markdown, $matches, PREG_MULTILINE);
            
            return response()->json([
                'character_count' => mb_strlen($markdown),
                'word_count' => str_word_count($plainText),
                'line_count' => count($lines),
                'paragraph_count' => count(array_filter($lines, function($line) {
                    return !empty(trim($line)) && !preg_match('/^#/', $line);
                })),
                'heading_count' => count($headings),
                'headings_by_level' => array_count_values(array_column($headings, 'level')),
                'code_blocks' => $codeBlocks,
                'links' => $links,
                'images' => $images,
                'tables' => $tables > 0 ? 1 : 0,
                'lists' => $lists,
                'numbered_lists' => $numberedLists,
                'reading_time' => $this->markdownService->calculateReadingTime($markdown),
                'excerpt' => $this->markdownService->generateExcerpt($markdown),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Stats generation failed',
                'message' => 'Unable to generate markdown statistics',
            ], 500);
        }
    }

    /**
     * Convert markdown to different formats.
     */
    public function convert(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'markdown' => 'required|string|max:100000',
            'format' => 'required|in:html,plain,excerpt',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 400);
        }

        $markdown = $request->input('markdown');
        $format = $request->input('format');
        
        try {
            $result = match ($format) {
                'html' => $this->markdownService->toHtml($markdown),
                'plain' => $this->markdownService->toPlainText($markdown),
                'excerpt' => $this->markdownService->generateExcerpt($markdown),
            };

            return response()->json([
                'format' => $format,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Conversion failed',
                'message' => 'Unable to convert markdown',
            ], 500);
        }
    }
}