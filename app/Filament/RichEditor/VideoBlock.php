<?php

namespace App\Filament\RichEditor;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;
use Filament\Forms\Components\TextInput;

/**
 * Blok video untuk editor konten: tautan YouTube atau berkas video unggahan.
 * Disimpan di konten sebagai <div data-type="customBlock" data-id="video" data-config="...">
 * dan diubah menjadi pemutar video oleh Situs::konten() saat halaman publik ditampilkan.
 */
class VideoBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'video';
    }

    public static function getLabel(): string
    {
        return __('Video');
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalHeading(__('Sisipkan video'))
            ->modalDescription(__('Isi tautan YouTube, atau unggah berkas video. Bila keduanya diisi, tautan YouTube yang dipakai.'))
            ->schema([
                TextInput::make('url')
                    ->label(__('Tautan YouTube'))
                    ->placeholder('https://www.youtube.com/watch?v=...')
                    ->url()
                    ->rule(fn () => function (string $attribute, mixed $value, \Closure $fail) {
                        if (filled($value) && ! self::idYoutube((string) $value)) {
                            $fail(__('Tautan ini bukan tautan video YouTube yang dikenali.'));
                        }
                    })
                    ->requiredWithout('berkas'),
                FileUpload::make('berkas')
                    ->label(__('Berkas video'))
                    ->disk('public')->visibility('public')->directory('posts/video')
                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                    ->maxSize(10240)
                    ->helperText(__('MP4 atau WebM, paling besar 10 MB. Untuk video yang lebih besar, pakai tautan YouTube.'))
                    ->requiredWithout('url'),
                TextInput::make('keterangan')
                    ->label(__('Keterangan (opsional)'))
                    ->maxLength(200),
            ]);
    }

    /** @param array<string, mixed> $config */
    public static function getPreviewLabel(array $config): string
    {
        $sumber = self::idYoutube((string) ($config['url'] ?? '')) ? 'YouTube' : basename((string) self::berkas($config));

        return __('Video') . ': ' . ($config['keterangan'] ?? null ?: $sumber);
    }

    /**
     * @param array<string, mixed> $config
     * @param array<string, mixed> $data
     */
    public static function toHtml(array $config, array $data): ?string
    {
        $keterangan = filled($config['keterangan'] ?? null) ? '<figcaption>' . e($config['keterangan']) . '</figcaption>' : '';

        if ($id = self::idYoutube((string) ($config['url'] ?? ''))) {
            return '<figure class="video"><div class="vframe"><iframe src="https://www.youtube-nocookie.com/embed/' . e($id) . '" title="' . e($config['keterangan'] ?? 'Video') . '"'
                . ' loading="lazy" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>' . $keterangan . '</figure>';
        }

        if ($path = self::berkas($config)) {
            return '<figure class="video"><video controls preload="metadata" src="/storage/' . e(ltrim($path, '/')) . '"></video>' . $keterangan . '</figure>';
        }

        return null;
    }

    /** ID video dari berbagai bentuk tautan YouTube, atau null. */
    public static function idYoutube(string $url): ?string
    {
        return preg_match('#(?:youtu\.be/|youtube(?:-nocookie)?\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/))([A-Za-z0-9_-]{11})#', $url, $m) ? $m[1] : null;
    }

    /** @param array<string, mixed> $config */
    private static function berkas(array $config): ?string
    {
        $berkas = $config['berkas'] ?? null;
        $berkas = is_array($berkas) ? reset($berkas) : $berkas;

        return is_string($berkas) && $berkas !== '' ? $berkas : null;
    }
}
