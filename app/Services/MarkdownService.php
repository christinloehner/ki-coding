<?php

namespace App\Services;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TaskList\TaskListExtension;
use League\CommonMark\Extension\Strikethrough\StrikethroughExtension;
use League\CommonMark\Extension\Autolink\AutolinkExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MarkdownService
{
    private MarkdownConverter $converter;
    
    /**
     * Create a new markdown service instance.
     */
    public function __construct()
    {
        $this->converter = $this->createConverter();
    }
    
    /**
     * Create the markdown converter with extensions.
     */
    private function createConverter(): MarkdownConverter
    {
        $environment = new Environment([
            'allow_unsafe_links' => false,
            'html_input' => 'strip',
            'max_nesting_level' => 10,
            'commonmark' => [
                'enable_em' => true,
                'enable_strong' => true,
                'use_asterisk' => true,
                'use_underscore' => true,
                'unordered_list_markers' => ['-', '*', '+'],
            ],
            'table' => [
                'wrap' => [
                    'enabled' => true,
                    'tag' => 'div',
                    'attributes' => ['class' => 'table-responsive'],
                ],
            ],
            'disallowed_raw_html' => [
                'disallowed_tags' => [
                    'script',
                    'style',
                    'iframe',
                    'object',
                    'embed',
                    'form',
                    'input',
                    'textarea',
                    'button',
                    'select',
                    'option',
                    'link',
                    'meta',
                    'title',
                    'head',
                    'body',
                    'html',
                ],
            ],
        ]);
        
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());
        $environment->addExtension(new DisallowedRawHtmlExtension());
        
        return new MarkdownConverter($environment);
    }
    
    /**
     * Convert markdown to HTML.
     */
    public function toHtml(string $markdown): string
    {
        if (empty($markdown)) {
            return '';
        }
        
        // Cache the conversion for better performance
        $cacheKey = 'markdown:' . md5($markdown);
        
        return Cache::remember($cacheKey, 3600, function () use ($markdown) {
            $html = $this->converter->convert($markdown)->getContent();
            
            // Additional security cleaning
            $html = $this->sanitizeHtml($html);
            
            // Add syntax highlighting classes
            $html = $this->addSyntaxHighlighting($html);
            
            // Add responsive classes to images
            $html = $this->makeImagesResponsive($html);
            
            // Add target="_blank" to external links
            $html = $this->addTargetBlankToExternalLinks($html);
            
            return $html;
        });
    }
    
    /**
     * Extract plain text from markdown.
     */
    public function toPlainText(string $markdown): string
    {
        if (empty($markdown)) {
            return '';
        }
        
        // Convert to HTML first
        $html = $this->toHtml($markdown);
        
        // Strip HTML tags
        $text = strip_tags($html);
        
        // Clean up whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }
    
    /**
     * Generate excerpt from markdown.
     */
    public function generateExcerpt(string $markdown, int $length = 160): string
    {
        $plainText = $this->toPlainText($markdown);
        
        if (mb_strlen($plainText) <= $length) {
            return $plainText;
        }
        
        return mb_substr($plainText, 0, $length) . '...';
    }
    
    /**
     * Calculate reading time for markdown content.
     */
    public function calculateReadingTime(string $markdown): int
    {
        $plainText = $this->toPlainText($markdown);
        $wordCount = str_word_count($plainText);
        
        // Average reading speed: 200 words per minute
        $readingTime = ceil($wordCount / 200);
        
        return max(1, $readingTime);
    }
    
    /**
     * Extract headings from markdown for table of contents.
     */
    public function extractHeadings(string $markdown): array
    {
        $headings = [];
        $lines = explode("\n", $markdown);
        
        foreach ($lines as $line) {
            if (preg_match('/^(#{1,6})\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $text = trim($matches[2]);
                $slug = Str::slug($text);
                
                $headings[] = [
                    'level' => $level,
                    'text' => $text,
                    'slug' => $slug,
                ];
            }
        }
        
        return $headings;
    }
    
    /**
     * Add table of contents to markdown.
     */
    public function addTableOfContents(string $markdown): string
    {
        $headings = $this->extractHeadings($markdown);
        
        if (empty($headings)) {
            return $markdown;
        }
        
        $toc = "\n## Inhaltsverzeichnis\n\n";
        
        foreach ($headings as $heading) {
            $indent = str_repeat('  ', $heading['level'] - 1);
            $toc .= "{$indent}- [{$heading['text']}](#{$heading['slug']})\n";
        }
        
        $toc .= "\n---\n\n";
        
        return $toc . $markdown;
    }
    
    /**
     * Sanitize HTML output.
     */
    private function sanitizeHtml(string $html): string
    {
        // Remove any remaining dangerous attributes
        $html = preg_replace('/on\w+="[^"]*"/i', '', $html);
        $html = preg_replace('/on\w+=\'[^\']*\'/i', '', $html);
        $html = preg_replace('/javascript:/i', '', $html);
        $html = preg_replace('/vbscript:/i', '', $html);
        $html = preg_replace('/data:/i', '', $html);
        
        return $html;
    }
    
    /**
     * Add syntax highlighting classes to code blocks.
     */
    private function addSyntaxHighlighting(string $html): string
    {
        // Add classes for syntax highlighting
        $html = preg_replace('/<pre><code class="language-(\w+)">/i', '<pre><code class="language-$1 hljs">', $html);
        $html = preg_replace('/<pre><code>/i', '<pre><code class="hljs">', $html);
        
        return $html;
    }
    
    /**
     * Make images responsive.
     */
    private function makeImagesResponsive(string $html): string
    {
        return preg_replace('/<img([^>]*)>/i', '<img$1 class="img-fluid" loading="lazy">', $html);
    }
    
    /**
     * Add target="_blank" to external links.
     */
    private function addTargetBlankToExternalLinks(string $html): string
    {
        return preg_replace_callback('/<a\s+href="([^"]*)"([^>]*)>/i', function ($matches) {
            $href = $matches[1];
            $attributes = $matches[2];
            
            // Check if it's an external link
            if (preg_match('/^https?:\/\//', $href) && !str_contains($href, config('app.url'))) {
                if (!str_contains($attributes, 'target=')) {
                    $attributes .= ' target="_blank" rel="noopener noreferrer"';
                }
            }
            
            return '<a href="' . $href . '"' . $attributes . '>';
        }, $html);
    }
    
    /**
     * Preview markdown conversion (for live preview).
     */
    public function preview(string $markdown): string
    {
        // For live preview, we don't cache
        $html = $this->converter->convert($markdown)->getContent();
        
        // Apply same sanitization and enhancements
        $html = $this->sanitizeHtml($html);
        $html = $this->addSyntaxHighlighting($html);
        $html = $this->makeImagesResponsive($html);
        $html = $this->addTargetBlankToExternalLinks($html);
        
        return $html;
    }
    
    /**
     * Validate markdown content.
     */
    public function validate(string $markdown): array
    {
        $errors = [];
        
        // Check for common issues
        if (mb_strlen($markdown) > 100000) {
            $errors[] = 'Markdown content is too long (maximum 100,000 characters)';
        }
        
        // Check for too many headings
        $headings = $this->extractHeadings($markdown);
        if (count($headings) > 50) {
            $errors[] = 'Too many headings (maximum 50 headings)';
        }
        
        // Check for suspicious patterns
        $suspiciousPatterns = [
            '/<script/i' => 'Script tags are not allowed',
            '/<iframe/i' => 'Iframe tags are not allowed',
            '/javascript:/i' => 'JavaScript URLs are not allowed',
            '/vbscript:/i' => 'VBScript URLs are not allowed',
            '/on\w+=/i' => 'Event handlers are not allowed',
        ];
        
        foreach ($suspiciousPatterns as $pattern => $message) {
            if (preg_match($pattern, $markdown)) {
                $errors[] = $message;
            }
        }
        
        return $errors;
    }
}