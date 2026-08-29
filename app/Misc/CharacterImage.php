<?php

namespace App\Misc;

use App\Models\Character;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Throwable;

/**
 * The two pictures a sheet carries, and the one place either of them is taken in.
 *
 * A player uploads whatever their phone or their image generator produced, which
 * is routinely a 12-megapixel photograph several times larger than PHP will
 * accept. Refusing those was the old behaviour and it refused them silently, so
 * nothing is refused for being large any more: the picture is scaled down to the
 * size the sheet actually shows it at and stored at that size. Only a file too
 * large to open at all is turned away, and it is turned away with a sentence.
 *
 * Everything is stored as JPEG. These are photographs and paintings — there is
 * nothing to keep a palette or an alpha channel for, and one format means the
 * printed sheet never meets something the browser's print path cannot draw.
 */
final class CharacterImage
{
    /** The likeness: the face, in the frame at the top of the sheet, and on the printed one. */
    public const PORTRAIT = 'portrait';

    /** The scene behind the name — landscape, and cropped to whatever room the header has. */
    public const BANNER = 'banner';

    /**
     * The box each picture is scaled down to fit inside, in pixels.
     *
     * Twice what the sheet shows, so it stays sharp on a phone's doubled screen
     * and has enough left for the printed portrait at 300dpi.
     *
     * @var array<string, array{int, int}>
     */
    public const BOXES = [
        self::PORTRAIT => [900, 1200],
        self::BANNER   => [2000, 1200],
    ];

    /**
     * What the upload itself may weigh, in kilobytes.
     *
     * Generous, because the browser has already shrunk anything ordinary before
     * it got here — this is the guard for the case where it could not.
     */
    public const MAX_KILOBYTES = 15360;

    /**
     * The longest edge that can be decoded without trouble.
     *
     * GD holds an uncompressed bitmap while it works: four bytes a pixel, so a
     * 10000 × 10000 picture is four hundred megabytes and the process dies
     * without a word. Refuse those at validation, where there is a message.
     */
    public const MAX_PIXELS = 10000;

    /** @return list<string> */
    public static function shapes(): array
    {
        return [self::PORTRAIT, self::BANNER];
    }

    /**
     * @return array<string, list<string>>
     */
    public static function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp,avif',
                'max:'.self::MAX_KILOBYTES,
                'dimensions:max_width='.self::MAX_PIXELS.',max_height='.self::MAX_PIXELS,
            ],
        ];
    }

    /**
     * Laravel's own wording for these is about files and megabytes. A player is
     * being told why their picture did not go on, so say that instead.
     *
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'image.required'   => 'Choose a picture to upload.',
            'image.image'      => 'That file is not a picture. JPEG, PNG, GIF, WebP and AVIF all work.',
            'image.mimes'      => 'That kind of picture cannot be used. JPEG, PNG, GIF, WebP and AVIF all work.',
            'image.max'        => 'That picture is larger than '.(self::MAX_KILOBYTES / 1024).' MB. Save a smaller copy and try again.',
            'image.dimensions' => 'That picture is more than '.number_format(self::MAX_PIXELS).' pixels across. Save a smaller copy and try again.',
            'image.uploaded'   => 'The picture did not finish uploading — it may be larger than this server accepts. Try a smaller copy.',
        ];
    }

    /**
     * Scale the picture down, store it, and give back the path to keep.
     *
     * The previous file for the same shape goes: nothing else points at it, and
     * a party's worth of replaced photographs adds up on a small disk.
     *
     * @throws \Intervention\Image\Exceptions\DecoderException when the file cannot be read as a picture
     */
    public static function store(UploadedFile $file, Character $character, string $shape): string
    {
        [$width, $height] = self::BOXES[$shape];

        $image = ImageManager::gd()
            ->read($file->getRealPath())
            // A phone writes the picture the way the sensor saw it and leaves a
            // note saying which way up it goes. Honour the note, then forget it.
            ->orient()
            ->scaleDown($width, $height)
            // Nothing here has an alpha channel worth keeping, and JPEG has
            // nowhere to put one — blend it into the frame's own green rather
            // than the black or white it would otherwise become.
            ->blendTransparency('#132e26');

        $path = 'characters/'.$character->slug.'/'.$shape.'-'.Str::lower(Str::random(12)).'.jpg';

        Storage::disk('public')->put($path, (string) $image->toJpeg(quality: 82));

        self::forget($character->{self::column($shape)});

        return $path;
    }

    /**
     * Take a picture back off the sheet.
     *
     * The backdrop then goes back to the house picture every sheet starts with,
     * and the likeness back to no frame at all — which is what *Use the default*
     * and *Remove* on the Manage sheet cards do.
     */
    public static function clear(Character $character, string $shape): void
    {
        $column = self::column($shape);

        self::forget($character->{$column});

        $character->update([$column => null]);
    }

    /** The column on `characters` that holds the given shape. */
    public static function column(string $shape): string
    {
        return $shape === self::PORTRAIT ? 'avatar' : 'banner';
    }

    /**
     * The URL for a stored picture, or null when there is none — or when the
     * file behind it has gone, which a restored database or a cleared disk both
     * manage. A path in the column is not a promise that the file is there.
     */
    public static function url(?string $path): ?string
    {
        if ($path === null || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Delete a picture that nothing points at any more.
     *
     * Best effort: a file already gone, or a disk that will not have it deleted,
     * is not worth failing an upload the player can see succeeded.
     */
    private static function forget(?string $path): void
    {
        if ($path === null || $path === '') {
            return;
        }

        try {
            Storage::disk('public')->delete($path);
        } catch (Throwable) {
            // Left behind, and harmless.
        }
    }
}
