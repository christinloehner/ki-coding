<?php

namespace App\Http\Controllers\Wiki;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

/**
 * Sicherer File-Upload-Controller für Wiki-Dateien
 */
class FileUploadController extends Controller
{
    /**
     * Erlaubte Dateitypen für verschiedene Upload-Kategorien
     */
    private const ALLOWED_TYPES = [
        'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        'document' => ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt'],
        'archive' => ['zip', 'rar', '7z', 'tar', 'gz'],
        'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'],
        'audio' => ['mp3', 'wav', 'ogg', 'flac', 'aac'],
    ];

    /**
     * Maximale Dateigröße in Bytes (10MB)
     */
    private const MAX_FILE_SIZE = 10 * 1024 * 1024;

    /**
     * Gefährliche Dateierweiterungen die blockiert werden
     */
    private const DANGEROUS_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'phtml', 'pht', 'phps',
        'asp', 'aspx', 'jsp', 'jspx', 'cfm', 'cgi', 'pl', 'py',
        'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js',
        'jar', 'sh', 'ps1', 'msi', 'dll', 'so', 'dylib'
    ];

    /**
     * Bild-Upload für Wiki-Artikel
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120', // 5MB
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validierung fehlgeschlagen',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            
            // Sicherheitsprüfungen
            if (!$this->isFileSecure($file)) {
                return response()->json([
                    'error' => 'Datei nicht sicher oder beschädigt'
                ], 400);
            }

            // Eindeutigen Dateinamen generieren
            $filename = $this->generateSecureFilename($file);
            
            // Speicherpfad erstellen
            $year = date('Y');
            $month = date('m');
            $path = "wiki/images/{$year}/{$month}";
            
            // Datei speichern
            $savedPath = $file->storeAs($path, $filename, 'public');
            
            // Thumbnail erstellen
            $thumbnailPath = $this->createThumbnail($savedPath);
            
            // Metadaten sammeln
            $metadata = [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'alt_text' => $request->alt_text,
                'caption' => $request->caption,
                'uploaded_by' => auth()->id(),
                'uploaded_at' => now(),
            ];

            // Erfolgsantwort
            return response()->json([
                'success' => true,
                'url' => Storage::url($savedPath),
                'thumbnail' => Storage::url($thumbnailPath),
                'filename' => $filename,
                'metadata' => $metadata
            ]);

        } catch (\Exception $e) {
            Log::error('Upload failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Upload fehlgeschlagen'
            ], 500);
        }
    }

    /**
     * Dokument-Upload für Wiki-Artikel
     */
    public function uploadDocument(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:pdf,doc,docx,txt,rtf,odt|max:10240', // 10MB
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validierung fehlgeschlagen',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            
            // Sicherheitsprüfungen
            if (!$this->isFileSecure($file)) {
                return response()->json([
                    'error' => 'Datei nicht sicher oder beschädigt'
                ], 400);
            }

            // Eindeutigen Dateinamen generieren
            $filename = $this->generateSecureFilename($file);
            
            // Speicherpfad erstellen
            $year = date('Y');
            $month = date('m');
            $path = "wiki/documents/{$year}/{$month}";
            
            // Datei speichern
            $savedPath = $file->storeAs($path, $filename, 'public');
            
            // Metadaten sammeln
            $metadata = [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'title' => $request->title,
                'description' => $request->description,
                'uploaded_by' => auth()->id(),
                'uploaded_at' => now(),
            ];

            return response()->json([
                'success' => true,
                'url' => Storage::url($savedPath),
                'filename' => $filename,
                'metadata' => $metadata
            ]);

        } catch (\Exception $e) {
            Log::error('Document upload failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Upload fehlgeschlagen'
            ], 500);
        }
    }

    /**
     * Prüft ob eine Datei sicher ist
     */
    private function isFileSecure($file): bool
    {
        // Dateierweiterung prüfen
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            return false;
        }

        // Dateigröße prüfen
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            return false;
        }

        // MIME-Type prüfen
        $mimeType = $file->getMimeType();
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
            'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain', 'text/rtf', 'application/vnd.oasis.opendocument.text'
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            return false;
        }

        // Dateiinhalt prüfen (einfache Malware-Erkennung)
        $content = file_get_contents($file->getRealPath());
        
        // Nach verdächtigen Patterns suchen
        $maliciousPatterns = [
            '<?php', '<%', '<script', 'javascript:', 'vbscript:', 'onload=', 'onerror=',
            'eval(', 'base64_decode', 'shell_exec', 'system(', 'exec(', 'passthru('
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                Log::warning("Malicious pattern detected in upload: {$pattern}");
                return false;
            }
        }

        return true;
    }

    /**
     * Generiert einen sicheren Dateinamen
     */
    private function generateSecureFilename($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Bereinige den ursprünglichen Namen
        $cleanName = preg_replace('/[^a-zA-Z0-9\-_]/', '', $originalName);
        $cleanName = substr($cleanName, 0, 50); // Maximal 50 Zeichen
        
        // Füge Timestamp und zufällige Komponente hinzu
        $timestamp = time();
        $random = Str::random(8);
        
        return "{$cleanName}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Erstellt ein Thumbnail für Bilder
     */
    private function createThumbnail(string $imagePath): string
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        $thumbnailPath = str_replace('.', '_thumb.', $imagePath);
        $thumbnailFullPath = storage_path('app/public/' . $thumbnailPath);
        
        try {
            // Thumbnail erstellen (300x300 max)
            Image::make($fullPath)
                ->fit(300, 300)
                ->save($thumbnailFullPath, 80);
                
            return $thumbnailPath;
        } catch (\Exception $e) {
            Log::error('Thumbnail creation failed: ' . $e->getMessage());
            return $imagePath; // Original zurückgeben wenn Thumbnail fehlschlägt
        }
    }

    /**
     * Datei löschen
     */
    public function deleteFile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'filename' => 'required|string',
            'type' => 'required|in:image,document',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validierung fehlgeschlagen',
                'messages' => $validator->errors()
            ], 422);
        }

        try {
            $filename = $request->filename;
            $type = $request->type;
            
            // Pfad rekonstruieren
            $year = date('Y');
            $month = date('m');
            $path = "wiki/{$type}s/{$year}/{$month}/{$filename}";
            
            // Datei löschen
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                
                // Thumbnail löschen falls vorhanden
                if ($type === 'image') {
                    $thumbnailPath = str_replace('.', '_thumb.', $path);
                    if (Storage::disk('public')->exists($thumbnailPath)) {
                        Storage::disk('public')->delete($thumbnailPath);
                    }
                }
                
                return response()->json(['success' => true]);
            }
            
            return response()->json(['error' => 'Datei nicht gefunden'], 404);
            
        } catch (\Exception $e) {
            Log::error('File deletion failed: ' . $e->getMessage());
            return response()->json(['error' => 'Löschung fehlgeschlagen'], 500);
        }
    }

    /**
     * Listet hochgeladene Dateien auf
     */
    public function listFiles(Request $request): JsonResponse
    {
        $type = $request->get('type', 'all');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);
        
        try {
            $files = [];
            $basePath = storage_path('app/public/wiki/');
            
            if ($type === 'all' || $type === 'image') {
                $imageFiles = $this->getFilesFromDirectory($basePath . 'images/', 'image');
                $files = array_merge($files, $imageFiles);
            }
            
            if ($type === 'all' || $type === 'document') {
                $documentFiles = $this->getFilesFromDirectory($basePath . 'documents/', 'document');
                $files = array_merge($files, $documentFiles);
            }
            
            // Paginierung
            $total = count($files);
            $offset = ($page - 1) * $perPage;
            $paginatedFiles = array_slice($files, $offset, $perPage);
            
            return response()->json([
                'files' => $paginatedFiles,
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'has_more' => $offset + $perPage < $total
            ]);
            
        } catch (\Exception $e) {
            Log::error('File listing failed: ' . $e->getMessage());
            return response()->json(['error' => 'Auflistung fehlgeschlagen'], 500);
        }
    }

    /**
     * Hilfsmethode zum Abrufen von Dateien aus einem Verzeichnis
     */
    private function getFilesFromDirectory(string $directory, string $type): array
    {
        $files = [];
        
        if (!is_dir($directory)) {
            return $files;
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && !str_contains($file->getFilename(), '_thumb.')) {
                $relativePath = str_replace(storage_path('app/public/'), '', $file->getPathname());
                $files[] = [
                    'name' => $file->getFilename(),
                    'path' => $relativePath,
                    'url' => Storage::url($relativePath),
                    'size' => $file->getSize(),
                    'type' => $type,
                    'modified' => $file->getMTime(),
                ];
            }
        }
        
        return $files;
    }
}
