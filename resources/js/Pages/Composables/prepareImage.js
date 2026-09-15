/** Below this, a picture is small enough to send as it is. */
const LEAVE_ALONE = 400 * 1024;

/**
 * Shrink a picture in the browser, before it is uploaded.
 *
 * A photograph off a phone is a dozen megabytes, and a server has a limit on
 * what it will take in one request — one that a player cannot see and that
 * PHP enforces by throwing the request away. Redrawing the picture at the size
 * the sheet shows it at turns those twelve megabytes into a few hundred
 * kilobytes, so the limit is never met in the first place.
 *
 * Anything that goes wrong here hands back the original file: the server scales
 * it too, and would rather have the picture slowly than not at all.
 *
 * @param {File} file
 * @param {number} longestEdge
 * @returns {Promise<File>}
 */
export async function shrink(file, longestEdge) {
    // An animated GIF would come back as one frame, which is not what the
    // player chose. Leave it, and let the server decide.
    if (! file.type.startsWith('image/') || file.type === 'image/gif') {
        return file;
    }

    let bitmap;

    try {
        // `from-image` bakes in the sideways note a phone camera leaves, so the
        // picture is drawn the way up it was taken.
        bitmap = await createImageBitmap(file, { imageOrientation: 'from-image' });
    } catch {
        return file;
    }

    try {
        const scale = Math.min(1, longestEdge / Math.max(bitmap.width, bitmap.height));

        if (scale === 1 && file.size <= LEAVE_ALONE) {
            return file;
        }

        const canvas = document.createElement('canvas');
        canvas.width = Math.round(bitmap.width * scale);
        canvas.height = Math.round(bitmap.height * scale);

        const context = canvas.getContext('2d');

        if (! context) {
            return file;
        }

        context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);

        const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', 0.85));

        if (! blob || blob.size >= file.size) {
            return file;
        }

        return new File([blob], file.name.replace(/\.[^.]+$/, '') + '.jpg', {
            type: 'image/jpeg',
            lastModified: Date.now(),
        });
    } catch {
        return file;
    } finally {
        bitmap.close();
    }
}

